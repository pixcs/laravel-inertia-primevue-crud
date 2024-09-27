<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from 'primevue/card';
import { Head, router, useForm } from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputText from 'primevue/inputtext';
import FloatLabel from 'primevue/floatlabel';
import Select from 'primevue/select';
import Button from 'primevue/button';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ConfirmPopup from 'primevue/confirmpopup';
import { useConfirm } from "primevue/useconfirm";
import { ref } from 'vue';
import Dialog from 'primevue/dialog';
import Checkbox from 'primevue/checkbox';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Drawer from 'primevue/drawer';
import axios from 'axios';


defineProps({
  movies: Array,
  permissions: Array,
  rolesWithPermissions: Array,
  roles: Object
})

const toast = useToast();
const confirm = useConfirm();
const showUpdateBtn = ref(false);

const products = ref([
  { code: 'admin', name: 'Full Access' },
  { code: 'editor', name: 'Edit Content' },
  { code: 'viewer', name: 'View Only' },
  // Add more hardcoded roles as needed
]);

const genreOptions = [
  { name: 'Action' },
  { name: 'Adventure' },
  { name: 'Romance' },
  { name: 'Comedy' },
  { name: 'Fiction' },
  { name: 'Sci-fi' },
  { name: 'Horror' }
];

const form = useForm({
  id: null,
  title: "",
  description: "",
  image: null,
  category: null,
  runningTime: "",
  year: "",
});

const onSubmitForm = async () => {
  console.log(showUpdateBtn.value);

  if (!showUpdateBtn.value) {
    form.post(route('movies-management.store'), {
      forceFormData: true,
      onSuccess: (response) => {
        console.log("Response data:", response);
        showSuccess('Movie Created!');
        form.reset();
      },
      onError: (errors) => {
        console.error(errors);
        showError('Failed to Create.');
        form.setErrors(errors);
      },
    });

  } else {
    console.log(form.value);
    form.post(route('movies-management.update'), {
      forceFormData: true,
      onSuccess: (response) => {
        console.log("Response updated data:", response);
        showSuccess('Updated Successfully');
        form.reset();
      },
      onError: (errors) => {
        console.error(errors);
        showError("Failed to Update");
        form.setErrors(errors);
      },
    })
  }

};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file; // Update the form's image field
    console.log("File selected:", file.name);
  }
};

const showSuccess = (detailMsg) => {
  toast.add({ severity: 'success', summary: 'Success', detail: detailMsg, life: 3000 });
};

const showError = (detailMsg) => {
  toast.add({ severity: 'error', summary: 'Error', detail: detailMsg, life: 3000 });
};

const showTemplateDelete = (event, id) => {
  confirm.require({
    target: event.currentTarget,
    group: 'templating',
    message: 'Do you want to to delete this?',
    icon: 'pi pi-exclamation-circle',
    rejectProps: {
      icon: 'pi pi-times',
      label: 'Cancel',
      outlined: true
    },
    acceptProps: {
      icon: 'pi pi-check',
      label: 'Confirm'
    },
    accept: () => {
      toast.add({ severity: 'info', summary: 'Deleted', detail: 'Deleted Successfully', life: 3000 });
      //console.log("movie id:", id);

      const movieForm = useForm({ id })

      movieForm.delete(route('movies-management.destroy'), {
        onSuccess: (response) => {
          console.log(response);
        },
        onError: (err) => {
          console.error(err);
        }
      })
    }
  });
}

const showTemplateEdit = (event, movie) => {
  console.log("Selected movie: ", movie);

  form.id = movie.id;
  form.title = movie.title;
  form.description = movie.description;
  form.image = movie.image;
  form.category = movie.genre;
  form.runningTime = movie.running_time;
  form.year = movie.year;

  showUpdateBtn.value = true;
  console.log("form new value:", form);

  confirm.require({
    target: event.currentTarget,
    group: 'templating',
    message: 'Do you want to edit this?',
    icon: 'pi pi-exclamation-circle',
    rejectProps: {
      icon: 'pi pi-times',
      label: 'Cancel',
      outlined: true
    },
    acceptProps: {
      icon: 'pi pi-check',
      label: 'Confirm'
    },
    accept: () => {
      toast.add({ severity: 'info', summary: 'Confirmed', detail: 'You have accepted', life: 3000 });
      //console.log("movie id:", id);

      const movieForm = useForm({ id })

      movieForm.post(route('movies-management.destroy'), {
        onSuccess: (response) => {
          console.log(response);
        },
        onError: (err) => {
          console.error(err);
        }
      })

    },
    reject: () => {
      form.reset();
      showUpdateBtn.value = false;
    }
  });
}

const roleVisible = ref(false);
const visible = ref(false);

const showRoleModal = () => {
  roleVisible.value = !roleVisible.value
}

const showModal = () => {
  visible.value = !visible.value
}

const roleForm = useForm({
  role: "",
  selectedPermissions: []
})

const submitNewRole = () => {
  console.log(roleForm);

  roleForm.post(route('role.store'), {
    onSuccess: (response) => {
      showRoleModal();
      showSuccess('Successfully Created');
      roleForm.reset();

      console.log("Successfully Added role ", response);
    },
    onError: (err) => {
      showError("Failed to Create New Role");
      console.error(err);
    }
  });
}

const visibleRight = ref(false);
const selectEditForm = useForm({
  role: "",
  createdAt: "",
  selectedPermissions: []
});

const showSelectedPermission = async (userId) => {
  console.log("ID: ", userId);
  visibleRight.value = true;

  try {
    const res = await axios.post(route('movies-management.find', userId));
    const roles = res.data.roles;
    console.log(roles);

    const permissions = roles.permissions.map(permission => [permission.name]);

    selectEditForm.role = roles.name;
    selectEditForm.createdAt = roles.created_at;
    selectEditForm.selectedPermissions = permissions.flat(1);
    console.log(selectEditForm);

  } catch (err) {
    console.error(err.response ? err.response.data : err.message);
  }
}


</script>

<template>

  <Head title="CRUD Movies" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Movies Management
      </h2>
      <pre class="text-gray-600">{{ roles }}</pre>
    </template>

    <Button icon="pi pi-user" label="Add Role" aria-label="Add Role" class="ml-5" @click="showRoleModal" />
    <Button icon="pi pi-key" label="Permissions" aria-label="Permissions" class="ml-5" @click="showModal" />

    <!-- Add Role  -->
    <Dialog v-model:visible="roleVisible" modal header="Manage Role" :style="{ width: '20rem' }" class="gap-y-5">
      <form @submit.prevent="submitNewRole">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">Add New Role </span>

        <FloatLabel>
          <InputText id="role" v-model="roleForm.role" class="w-full pb-2" />
          <label for="role">Role</label>
        </FloatLabel>
        <p v-if="roleForm.errors.role" class="text-xs text-red-500 font-bold mt-2">{{ roleForm.errors.role }}</p>

        <div class="flex items-center gap-2 my-2" v-for="permission of permissions" :key="permission.id">
          <Checkbox v-model="roleForm.selectedPermissions" :inputId="permission.name" name="permissions"
            :value="permission.name" />
          <label :for="permission.name" class="ml-2">{{ permission.name }}</label>
        </div>
        <p v-if="roleForm.errors.selectedPermissions" class="text-xs text-red-500 font-bold mt-2">{{
          roleForm.errors.selectedPermissions }}</p>

        <div class="flex justify-end gap-4 my-4">
          <Button type="button" label="Cancel" severity="secondary" @click="showRoleModal"></Button>
          <Button type="submit" label="Submit"></Button>
        </div>
      </form>
    </Dialog>

    <!-- Permissions Modal -->
    <Dialog v-model:visible="visible" modal header="Manage Permissions" :style="{ width: '60rem' }">
      <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
      <DataTable :value="rolesWithPermissions" showGridlines tableStyle="min-width: 50rem">
        <Column field="name" header="Role"></Column>
        <Column field="permissions" header="Permissions">
          <template #body="slotProps">
            <div v-for="permission in slotProps.data.permissions" :key="permission.id"
              class="flex items-center gap-4 mb-4">
              <div class="card flex flex-wrap justify-center gap-4">
                <p class="ml-2">{{ permission.name }}</p>
              </div>
            </div>
          </template>
        </Column>
        <Column field="name" header="Action">
          <template #body="slotProps">
            <form @submit.prevent="showSelectedPermission(slotProps.data.id)">
              <Button icon="pi pi-file-edit" type="submit" aria-label="Save" label="Edit" />
            </form>
            <Drawer v-model:visible="visibleRight" :header="selectEditForm.role" position="right">
              <p>Created At: {{ selectEditForm.createdAt }}</p>
              <form @submit.prevent="">
                <div class="flex items-center gap-2 my-5" v-for="permission of permissions" :key="permission.id">
                  <Checkbox v-model="selectEditForm.selectedPermissions" :inputId="permission.name" name="permissions"
                    :value="permission.name" />
                  <label :for="permission.name" class="ml-2">{{ permission.name }}</label>
                </div>
                <div class="flex justify-end gap-2">
                  <Button type="button" label="Cancel" severity="secondary" @click="visibleRight.value = false"></Button>
                  <Button type="submit" label="Update" @click=""></Button>
                </div>
              </form>
            </Drawer>
          </template>
        </Column>
      </DataTable>
    </Dialog>

    <div class="flex flex-wrap gap-5 ">
      <form class="overflow-y-auto h-[550px] p-5" @submit.prevent="onSubmitForm">
        <template class="flex flex-col gap-y-2">
          <InputLabel value="Title" class="text-gray-600" />
          <TextInput class="text-gray-600" v-model="form.title" />
          <p v-if="form.errors.title" class="text-xs text-red-500 font-bold">{{ form.errors.title }}</p>
        </template>

        <FloatLabel class="mt-6">
          <Textarea v-model="form.description" rows="5" cols="30" class="text-gray-600 w-full" />
          <label>Description</label>
          <p v-if="form.errors.description" class="text-xs text-red-500 font-bold">{{ form.errors.description }}</p>
        </FloatLabel>

        <input type="file" class="text-gray-600 mt-4" @change="handleFileUpload">
        <p v-if="form.errors.image" class="text-xs text-red-500 font-bold mt-2">{{ form.errors.image }}</p>

        <template class="flex flex-col mt-2 gap-y-2">
          <Select v-model="form.category" :options="genreOptions" optionLabel="name" optionValue="name"
            placeholder="Select a Genre" class="w-full md:w-56 text-white mt-2" />
          <p v-if="form.errors.category" class="text-xs text-red-500 font-bold mt-2">{{ form.errors.category }}</p>
        </template>

        <template class="flex flex-col gap-y-2">
          <InputLabel value="Running Time" class="text-gray-600 mt-2" />
          <TextInput class="text-gray-600" v-model="form.runningTime" />
          <p v-if="form.errors.runningTime" class="text-xs text-red-500 font-bold mt-2">{{ form.errors.runningTime }}
          </p>
        </template>

        <template class="flex flex-col gap-y-2">
          <InputLabel value="Year" class="text-gray-600 mt-2" />
          <TextInput class="text-gray-600" v-model="form.year" />
          <p v-if="form.errors.year" class="text-xs text-red-500 font-bold mt-2">{{ form.errors.year }}</p>
        </template>

        <Toast />
        <PrimaryButton v-if="!showUpdateBtn" class="mt-4">Submit</PrimaryButton>
        <PrimaryButton v-if="showUpdateBtn" class="mt-4">Update</PrimaryButton>
      </form>

      <Card style="width: 16rem; overflow: hidden" v-for="movie of movies" :key="movie.id" class="relative">
        <template #header>
          <img class="w-full h-52 object-cover object-center hover:scale-110 transition duration-500"
            :src="`/storage/images/${movie.image}`" alt="Movie header" />
        </template>
        <template #title>{{ movie.title }}</template>
        <!-- {{ movie.id }} -->
        <template #subtitle>{{ movie.genre }}</template>
        <template #content>
          <p class="m-0 text-xs">{{ movie.description }}</p>
        </template>

        <template #footer>
          <div class="flex justify-between gap-4 mt-1">
            <p class="text-xs text-gray-500">{{ movie.running_time }}</p>
            <p class="text-xs text-gray-500">{{ movie.year }}</p>
            <button
              class="pi pi-file-edit bg-slate-900/90 p-2 rounded-md text-5xl text-green-300 cursor-pointer hover:scale-110 transition duration-100 absolute top-10 right-14"
              @click="showTemplateEdit($event, movie)">
            </button>
            <button
              class="pi pi-delete-left bg-slate-900/90 p-2 rounded-md text-5xl text-red-500 cursor-pointer hover:scale-110 transition duration-100 absolute top-10 right-5"
              @click="showTemplateDelete($event, movie.id)">
            </button>
            <ConfirmPopup group="templating">
              <template #message="slotProps">
                <div
                  class="flex flex-col items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700 p-4 mb-4 pb-0">
                  <i :class="slotProps.message.icon" class="text-6xl text-primary-500"></i>
                  <p>{{ slotProps.message.message }}</p>
                </div>
              </template>
            </ConfirmPopup>
          </div>
        </template>
      </Card>

    </div>
  </AuthenticatedLayout>
</template>
