
<template>
    <Head title="Main" />

    <AuthenticatedLayout @click="handleClick">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                New Job Posts
            </h2>
        </template>

        <template #notification>
            <h3 class="text-xs p-2 text-blue-600 font-bold border-solid border-b-2">Notification ({{ notification.length }})</h3>
            <div v-if="notification.length === 0">
                <div>0 Notification</div>
            </div>
            <div v-else>
                <div v-for="(item, index) in notification" :key="item.id">
                    <div class="notif-container p-2 border-solid border-b cursor-pointer hover:bg-gray-200" @click="viewJob(item.record_id)">
                        <div class="text-xs font-bold">{{ item.type === "new_email"?"New User Detected":"Posted Job" }}</div>
                        <div class="text-xs font-normal">{{ item.message }}</div>
                        <div class="text-xs font-bold text-gray-500">{{ moment(item.created_at).format('MMM DD, YYYY - hh:mm A') }}</div>
                    </div>
                </div>
            </div>
        </template>

        <template #count>{{notification_count}}</template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="w-full">
                            <thead>
                                <th class="border-solid border p-3">Job Post</th>
                                <th class="border-solid border p-3">Post By</th>
                                <th class="border-solid border p-3">Posted Date</th>
                                <th class="border-solid border p-3"></th>
                            </thead>
                            <tbody v-if="posted_job.length > 0">
                                <tr v-for="(data, index) in posted_job" :key="data.id">
                                    <td class="border-solid border p-3">{{ data.title }}</td>
                                    <td class="border-solid border p-3">{{ data.email }}</td>
                                    <td class="border-solid border p-3">{{ moment(data.created_at).format('MMM DD, YYYY') }}</td>
                                    <td class="border-solid border p-3 text-center">
                                        <PrimaryButton class="mr-2" @click="viewJob(data.id)">VIEW</PrimaryButton>
                                        <PrimaryButton class="bg-green-500 mr-2" @click="openModal(data.id, 'Approve')" v-if="data.status === 0">Approve</PrimaryButton>
                                        <DangerButton @click="openModal(data.id, 'Reject')" v-if="data.status === 0">Reject</DangerButton>
                                        <span class="text-green-500" v-if="data.status === 1">Approved</span>
                                        <span class="text-red-500" v-if="data.status === 2">Rejected</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td class="border-solid border p-3 text-center" colspan="4">No jobs found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <Modal :show="showModal" @close="closeModal" maxWidth="xl">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">{{ actions }} Post</h2>
                <div class="mb-5">Are you sure you want to {{ actions }} this post?</div>
                <div class="flex justify-end">
                <PrimaryButton class="mr-2" @click="actionCommand">Yes</PrimaryButton> 
                <PrimaryButton @click="closeModal">No</PrimaryButton>
                </div>
            </div>
        </Modal>
        
        <Modal :show="showModal2" @close="closeModal" maxWidth="2xl">
            <div class="p-6">
                <div class="container-item">
                    <div class="grid">
                        <h2 class="relative lg:col-start-1 text-2xl font-extrabold text-blue-800 mb-1 uppercase">{{ view_job.title }}</h2>
                        <div class="relative lg:col-start-2 text-right">
                            <span class="capitalize text-xs py-1 px-2 bg-green-500 text-white rounded-md mr-1">{{ view_job.employment_type.replaceAll('_', ' ') }}</span> <span class="capitalize text-xs py-1 px-2 bg-orange-500 text-white rounded-md mr-1">{{ view_job.schedule.replaceAll('_', ' ') }}</span>
                            <br>
                            <span class="text-xs text-gray-800">Experience: {{ view_job.years_of_experience }} years</span>
                        </div>
                    </div>
                    
                    <div><b>Office</b>: {{ view_job.office }}</div>
                    <div><b>Department</b>: {{ view_job.department }}</div>
                    <div class="mb-5"><b>Category</b>: {{ view_job.recruiting_category !== '' ?view_job.recruiting_category:'-'}}</div>
                    <div v-html="view_job.description" class="description-text text-gray-800 font-normal mb-7"></div>
                </div>
                <div class="flex justify-end">
                    <PrimaryButton class="bg-green-500 mr-2" @click="openModal(view_job.id, 'Approve')" v-if="view_job.status === 0">Approve</PrimaryButton>
                    <DangerButton class="mr-2" @click="openModal(view_job.id, 'Reject')" v-if="view_job.status === 0">Reject</DangerButton>
                    <span class="text-green-500 mr-2" v-if="view_job.status === 1">Approved</span>
                    <span class="text-red-500 mr-2" v-if="view_job.status === 2">Rejected</span>
                    <PrimaryButton @click="closeModal">Close</PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>

    
</template>

<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, computed, reactive } from 'vue';

import toastr from 'toastr';
import axios from 'axios';
import moment from 'moment';
import 'toastr/build/toastr.min.css';

const posted_job = ref<any[]>([]);
const view_job = ref();
const notification = ref<any[]>([]);
const notification_count = ref();
const showModal = ref(false);
const showModal2 = ref(false);
const actions = ref("");
const store_id = ref();

toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: 'toast-bottom-right',
  timeOut: 3000
}

onMounted(async () => {

    window.Echo.channel('my-channel')
    .listen('.my-event', (e: any) => {
        if(e.message === "posted") {
            getNotification();
            getRecord();
            toastr.success('New Job Posted');
        }
    });
    
    getNotification();
    getRecord();

});


function openModal(id: number, action: string) {
  showModal.value = true;

  store_id.value = id;
  actions.value = action;
}

function closeModal() {
  showModal.value = false;
  showModal2.value = false;
}

const actionCommand = async () => {
    try {
        const response = await axios.put(`/posted-jobs/${store_id.value}`, { actions: actions.value });
        posted_job.value = response.data.postedJobs;

        closeModal();
    } catch (error) {
        console.error('POST error:', error);
    }
};


const getRecord = async () => {
    try {
        const response_data = await axios.get('/posted-jobs/get-new');
        posted_job.value = response_data.data.postedJobs;
        
    } catch(error){}
};

const getNotification = async () => {
    try {
        const response = await axios.get('/posted-jobs/get-notification');
        notification.value = response.data.notification_record;
        notification_count.value = response.data.notification_count;

    } catch (error) {
        console.error('POST error:', error);
    }
};

const handleClick = async () => {
    try {
        const response = await axios.put(`/posted-jobs/read-notif`);
        notification_count.value = response.data.notification_count;
    } catch (error) {
        console.error('POST error:', error);
    }
};

const viewJob = async (id: number) => {
    try {
        const response = await axios.get(`/posted-jobs/get-by-id/${id}`);
        view_job.value = response.data.postedJobs;

        showModal2.value = true;
    } catch (error) {
        console.error('POST error:', error);
    }
};

</script>


<style>
span.notif-count {
    background: red;
    display: inline-block;
    padding: 1px;
    color: white;
    border-radius: 50%;
    width: 17px;
    height: 17px;
    font-size: 10px;
    font-weight: bold !important;
}
</style>