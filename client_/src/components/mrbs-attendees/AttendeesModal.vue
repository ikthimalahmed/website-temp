<template>
  <div>
    <!-- model starts -->
    <Dialog
      header="Header"
      :visible="showModal"
      :style="{ width: '50vw' }"
      :closable="false"
      :modal="true"
    >
      <template #header>
        <h5>Guest contact details</h5>
      </template>

      <div v-if="validationErrors">
        <Message severity="error" sticky>
          <ul v-for="(feild, index) in validationErrors.errors" :key="index">
            <li>
              <span v-for="(detail, detailIndex) in feild" :key="detailIndex">
                {{ detail }}</span
              >
            </li>
          </ul>
        </Message>
      </div>

      <div class="p-fluid p-grid">
        <div class="p-col-12 p-md-12 p-tb-2">
          <label>Name of the guest</label>
          <InputText v-model="attendee.name" />
        </div>

        <div class="p-col-12 p-md-12">
          <label>Mobile No</label>
          <InputNumber v-model="attendee.mobile_no" :useGrouping="false" />
        </div>

        <div class="p-col-12 p-md-12">
          <label>Email</label>
          <InputText v-model="attendee.email" />
        </div>
      </div>

      <template #footer>
        <Button
          label="Close"
          icon="pi pi-times"
          @click="closeModal"
          class="p-button-text"
        />
        <Button
          label="Save"
          icon="pi pi-check"
          v-if="!attendee.id"
          @click="save"
          autofocus
        />
        <Button
          label="Update"
          icon="pi pi-check"
          v-if="attendee.id"
          @click="update"
          autofocus
        />
      </template>
    </Dialog>
  </div>
</template>

<script>
import axios from "@/components/infra/axios";
export default {
  data() {
    return {
      showModal: false,
      attendee: {
        name: "",
        mobile_no: null,
        email: "",
      },
      validationErrors: "",
    };
  },

  methods: {
    new() {
      this.resetData();
      this.showModal = true;
    },

    save() {
      return axios
        .post("/api/mrbs_attendees", this.attendee)
        .then((resp) => {
          this.attendee = resp.data;
        })
        .catch((err) => {
          this.validationErrors = err.data;
        });
    },

    edit(id) {
      this.resetData();
      this.getItem(id).then((resp) => {
        this.showModal = true;
      });
    },

    getItem(id) {
      return axios.get(`/api/mrbs_attendees/${id}`).then((resp) => {
        this.attendee = resp.data;
      });
    },

    update() {
      return axios
        .put(`/api/mrbs_attendees/${this.attendee.id}`, this.attendee)
        .then((resp) => {
          this.attendee = resp.data;
        });
    },

    remove(id) {
      this.$confirm.require({
        target: event.currentTarget,
        message: "Do you want to delete this record?",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: () => {
          return axios.delete(`/api/mrbs_attendees/${id}`).then((resp) => {
            this.$router.push({
              query: { ...this.$route.query, reload: true },
            });
          });
        },
        reject: () => {
          this.$toast.add({
            severity: "error",
            summary: "Rejected",
            detail: "You have rejected",
            life: 3000,
          });
        },
      });
    },

    resetData() {
      Object.assign(this.$data, this.$options.data());
    },

    closeModal() {
      this.$router.push({ query: { ...this.$route.query, reload: true } });
      this.showModal = false;
    },
  },
};
</script>