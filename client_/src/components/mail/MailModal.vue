<template>
  <div>
    <Dialog
      header="Header"
      :visible="showModal"
      :style="{ width: '60vw' }"
      :closable="false"
      :modal="true"
    >
      <template #header>
        <h5>Staff Contact</h5>
      </template>

      <Card>
        <template #content>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in items" :key="index">
                <td class="english">{{ index + 1 }}</td>
                <td>{{ item.pivot ? item.pivot.mrbs_booking_id : "" }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.mobile_no }}</td>

                <td>
                  <i class="pi pi-send" @click="sendMail(item.pivot)"></i>
                </td>
              </tr>
            </tbody>
          </table>
        </template>
      </Card>

      <template #footer>
        <Button
          label="Close"
          icon="pi pi-times"
          @click="closeModal"
          class="p-button-text"
        />
        <Button
          label="Send All"
          icon="pi pi-check"
          @click="sendAll(items)"
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
      items: [],
      selectedBooking: "",
    };
  },
  methods: {
    new() {
      this.showModal = true;
    },

    mailList(id) {
      this.selectedBooking = id;
      this.getItem(id).then((resp) => {
        this.showModal = true;
      });
    },

    getItem(id) {
      return axios.get(`/api/booking/mailList/${id}`).then((resp) => {
        this.items = resp.data;
      });
    },

    sendMail(id) {
      return axios.post(`/api/booking/sendMail`, id).then((resp) => {
        console.log("Success", resp);
      });
    },

    sendAll(id) {
      return axios
        .post(`/api/booking/sendAll/${this.selectedBooking}`, { attendees: id })
        .then((resp) => {
          console.log("Success", resp);
        });
    },

    closeModal() {
      this.$router.push({ query: { ...this.$route.query, reload: true } });
      this.showModal = false;
    },
  },
};
</script>