
<template>
    <Head title="Welcome" />

    <header class="px-10 py-5  bg-blue-400 fixed z-50 w-full items-center">
        <div class="grid relative w-full max-w-2xl px-6 lg:max-w-7xl m-auto">
            <div class="relative lg:col-start-1">
                <h1 class="round-md dark:text-white text-2xl font-extrabold text-left">OTA CODING CHALLENGE</h1>
            </div>
            <div class="relative lg:col-start-2 text-right">
                <PrimaryButton @click="openModal">Add Job</PrimaryButton>
            </div>
        </div>
        <div class="grid relative w-full max-w-2xl px-6 lg:max-w-7xl m-auto mt-3">
            <div class="flex flex-col gap-6 dark:text-black">
                <input type="text" v-model="searchBox" class="flex flex-col gap-2 rounded-md border-gray-300" placeholder="Search Job">
            </div>
        </div>
    </header>

    <div class="bg-gray-50 text-black/50 dark:bg-gray-300 dark:text-white/50 pt-32">
        <div class="relative flex min-h-screen flex-col items-center justify-start selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8">

                        <div class="flex flex-col gap-6 dark:text-black">
                            <div v-if="filteredJobs.length > 0">
                                <div v-for="(job, index) in filteredJobs" class="relative text-black rounded-lg bg-white p-6 mb-3">
                                    <div class="container-item">
                                        <div class="grid">
                                            <h2 class="relative lg:col-start-1 text-2xl font-extrabold text-blue-800 mb-1 uppercase">{{ job.title }}</h2>
                                            <div class="relative lg:col-start-2 text-right">
                                                <span class="capitalize text-xs py-1 px-2 bg-green-500 text-white rounded-md mr-1">{{ job.employment_type.replaceAll('_', ' ') }}</span> <span class="capitalize text-xs py-1 px-2 bg-orange-500 text-white rounded-md mr-1">{{ job.schedule.replaceAll('_', ' ') }}</span> <span class="capitalize text-xs py-1 px-2 bg-gray-500 text-white rounded-md">{{ job.type }}</span>
                                                <br>
                                                <span class="text-xs text-gray-800">Experience: {{ job.years_of_experience }} years</span>
                                            </div>
                                        </div>
                                        
                                        <div><b>Office</b>: {{ job.office }}</div>
                                        <div><b>Department</b>: {{ job.department }}</div>
                                        <div><b>Category</b>: {{ job.recruiting_category !== '' ?job.recruiting_category:'-'}}</div>
                                        <!-- <div v-html="job.description" class="description-text text-gray-800 font-normal"></div> -->
                                        <PrimaryButton class="block-btn relative block mt-3 w-full text-center" @click="viewJob(job.id, job.type)">View Full Job Details</PrimaryButton>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-center">No Jobs Found</div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <Modal :show="showModal" @close="closeModal" maxWidth="xl">
      <div class="p-6">
        <h2 class="text-lg font-semibold mb-4">Create New Job</h2>
        <div class="mb-5">Please fill up the following record</div>
        <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
            <div class="grid">
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="error email text-red-700">*</span></label>
                    <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" required/>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="error email text-red-700">*</span></label>
                    <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2" required/>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="error email text-red-700">*</span></label>
                    <textarea v-model="form.description" type="text" class="w-full border rounded px-3 py-2" required></textarea>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Company</label>
                    <input v-model="form.subcompany" type="text" class="w-full border rounded px-3 py-2" required/>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Office <span class="error email text-red-700">*</span></label>
                    <input v-model="form.office" type="text" class="w-full border rounded px-3 py-2" required/>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="error email text-red-700">*</span></label>
                    <select v-model="form.department" name="department" id="department" class="w-full border rounded px-3 py-2" required>
                        <option value=""></option>
                        <option v-for="(item, index) in department" :key="index" :value="item.name">{{ item.name }}</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schedule <span class="error email text-red-700">*</span></label>
                    <select v-model="form.schedule" name="schedule" id="schedule" class="w-full border rounded px-3 py-2" required>
                        <option value=""></option>
                        <option v-for="(item, index) in sched" :key="index" :value="item.name">{{ item.name.replaceAll('_',' ').toUpperCase() }}</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recruiting Category <span class="error email text-red-700">*</span></label>
                    <select v-model="form.recruiting_category" name="recruiting_category" id="recruiting_category" class="w-full border rounded px-3 py-2" required>
                        <option value=""></option>
                        <option v-for="(item, index) in cat" :key="index" :value="item.name">{{ item.name.replaceAll('_',' ').toUpperCase() }}</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employment Type <span class="error email text-red-700">*</span></label>
                    <select v-model="form.employment_type" name="employment_type" id="employment_type" class="w-full border rounded px-3 py-2" required>
                        <option value=""></option>
                        <option v-for="(item, index) in employmentType" :key="index" :value="item.name">{{ item.name.replaceAll('_',' ').toUpperCase() }}</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience <span class="error email text-red-700">*</span></label>
                    <input v-model="form.years_of_experience" type="number" class="w-full border rounded px-3 py-2" value="1" required/>
                </div>
            </div>
        </form>
        <div class="flex justify-end">
          <PrimaryButton class="mr-2" type="submit" @click="handleSubmit">SUBMIT</PrimaryButton> 
          <SecondaryButton @click="closeModal">CANCEL</SecondaryButton>
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
                <PrimaryButton @click="closeModal">Close</PrimaryButton>
            </div>
        </div>
    </Modal>

</template>

<script setup lang="ts">

import { Head } from '@inertiajs/vue3';
import { onMounted, ref, computed, reactive } from 'vue';
import axios from 'axios';

import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const posted_job = ref<any[]>([]);
const view_job = ref();
const searchBox = ref('');
const showModal = ref(false);
const showModal2 = ref(false);

type JobForm = {
  title: string;
  email: string;
  description: string;
  subcompany: string;
  office: string;
  department: string;
  recruiting_category: string;
  employment_type: string;
  years_of_experience: string;
  [key: string]: string;
};

const form = reactive<JobForm>({
  title: '',
  email: '',
  description: '',
  subcompany: '',
  schedule: '',
  office: '',
  department: '',
  recruiting_category: '',
  employment_type: '',
  years_of_experience: '',
});

const employmentType = ref<any[]>([]);
const department = ref<any[]>([]);
const cat = ref<any[]>([]);
const sched = ref<any[]>([]);

const filteredJobs = computed(() =>
  posted_job.value.filter((job) =>
    job.title.toLowerCase().includes(searchBox.value.toLowerCase())
  )
);

onMounted(async () => {
    
    getRecord();

    window.Echo.channel('my-channel')
    .listen('.my-event', (e: any) => {
        getRecord();
    });
    
});


function openModal() {
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  showModal2.value = false;
}

const handleSubmit = async () => {
    try {
        const response = await axios.post('/posted-jobs', form);

        resetForm();
        closeModal();
    } catch (error) {
        console.error('POST error:', error);
    }
};

const resetForm = () => {
    for (const key in form) {
        form[key] = '';
    }
};

const viewJob = async (id: number, type: string) => {
    try {
        // const response = await axios.get(`/posted-jobs/get-by-id/${id}`);
        view_job.value = posted_job.value.find(item => item.id === id);

        showModal2.value = true;
    } catch (error) {
        console.error('POST error:', error);
    }
};

const getRecord = async () => {
    try {
        
        const response_data = await axios.get('/posted-jobs/get');
        
        posted_job.value = [];

        for (let i = 0; i < response_data.data.postedJobs.length; i++) {
            const job = response_data.data.postedJobs[i];

            const employment = job.employment_type;
            const dept = job.department;
            const category = job.recruiting_category;
            const schedule = job.schedule;

            posted_job.value.push({
                id: job.id,
                title: job.title,
                description: job.description,
                subcompany: job.subcompany,
                office: job.office,
                recruiting_category: category,
                department: dept,
                employment_type: employment,
                schedule: schedule,
                years_of_experience: job.years_of_experience,
                type: 'Direct'
            });
        }

        const response = await axios.get('/api/job-feed', {
            responseType: 'text',
        });
        
        const parser = new DOMParser();
        const xml = parser.parseFromString(response.data, 'text/xml');
        const jobElements = xml.getElementsByTagName('position');

        for (let i = 0; i < jobElements.length; i++) {
            const job = jobElements[i];

            const employment = job.getElementsByTagName('employmentType')[0]?.textContent || '';
            const dept = job.getElementsByTagName('department')[0]?.textContent || '';
            const category = job.getElementsByTagName('recruitingCategory')[0]?.textContent || '';
            const schedule = job.getElementsByTagName('schedule')[0]?.textContent || '';

            posted_job.value.push({
                id: job.getElementsByTagName('id')[0]?.textContent || '',
                title: job.getElementsByTagName('name')[0]?.textContent || '',
                description: job.getElementsByTagName('jobDescription')[0]?.textContent || '',
                subcompany: job.getElementsByTagName('subcompany')[0]?.textContent || '',
                office: job.getElementsByTagName('office')[0]?.textContent || '',
                recruiting_category: category,
                department: dept,
                employment_type: employment,
                schedule: schedule,
                years_of_experience: job.getElementsByTagName('yearsOfExperience')[0]?.textContent || '',
                type: 'External'
            });

            if (employment && !employmentType.value.some(e => e.name === employment)) {
                employmentType.value.push({ name: employment });
            }

            if (dept && !department.value.some(d => d.name === dept)) {
                department.value.push({ name: dept });
            }

            if (schedule && !sched.value.some(d => d.name === schedule)) {
                sched.value.push({ name: schedule });
            }

            if (category && !cat.value.some(d => d.name === category)) {
                cat.value.push({ name: category });
            }
        }

    } catch (error) {
        console.error('Error: ', error);
    }
};

</script>

<style>

.container-item strong {
    font-weight: bold;
    color: #004395 !important;
}

.container-item li {
    list-style: disc;
    margin-left: 45px;
}

.container-item p {
    margin-bottom: 10px;
}

.block-btn {
    display: block;
}
</style>