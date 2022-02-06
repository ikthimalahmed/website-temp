<template>
  <div>
    <attendees-modal ref="attendeeDialog"> </attendees-modal>
    <Dialog
      header="Header"
      :visible="showModal"
      :style="{ width: '50vw' }"
      :closable="false"
      :modal="true"
    >
      <template #header>
        <h4>Booking Details</h4>
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
        <div class="p-col-12 p-md-12">
          <label>Name of the guest room</label>
          <Dropdown
            v-model="booking.room_id"
            :options="roomList"
            optionLabel="name"
            optionValue="id"
            placeholder="Select a room"
          />
        </div>

        <div class="p-col-6 p-md-6">
          <label>Check-in Date and Time</label>
          <Calendar
            v-model="booking.start_datetime"
            :showTime="true"
            hourFormat="24"
          />
        </div>

        <div class="p-col-6 p-md-6">
          <label>Check-out Date and Time</label>
          <Calendar
            v-model="booking.end_datetime"
            
          />
        </div>

        <div class="p-col-12 p-md-12">
          <label>Description</label>
          <InputText v-model="booking.description" />
        </div>

        <div class="p-col-12 p-md-12">
          <label>Guests</label>
          <MultiSelect
            v-model="booking.attendee_id"
            :options="attendeeList"
            display="chip"
            optionLabel="name"
            optionValue="id"
            placeholder="Select guests"
          >
          </MultiSelect>
        </div>

        <div class="p-col-6 p-md-6">
          <Button
            label="Add guests"
            icon="pi pi-plus"
            class="p-mr-2 p-button-sm"
            @click="$refs.attendeeDialog.new()"
          />
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
          v-if="!booking.id"
          @click="save"
          autofocus
        />
        <Button
          label="Update"
          icon="pi pi-check"
          v-if="booking.id"
          @click="update"
          autofocus
        />
      </template>
    </Dialog>
  </div>
</template>

<script>
import axios from "@/components/infra/axios";
import { format } from "date-fns";
import AttendeesModal from "@/components/mrbs-attendees/AttendeesModal.vue";
export default {
  data() {
    return {
      showModal: false,
      attendee: [],
      booking: {
        room_id: null,
        start_datetime: null,
        end_datetime: null,
        description: null,
        attendee_id: [],
      },
      validationErrors: "",
    };
  },

  components: {
    AttendeesModal,
  },

  methods: {
    new() {
      this.resetData();
      this.showModal = true;
    },

    save() {
      this.booking.start_datetime = this.booking.start_datetime
        ? format(new Date(this.booking.start_datetime), "yyyy-MM-dd HH:mm:ss")
        : "";
      this.booking.end_datetime = this.booking.end_datetime
        ? format(new Date(this.booking.end_datetime), "yyyy-MM-dd HH:mm:ss")
        : "";

      return axios
        .post("/api/booking/CreateBooking", this.booking)
        .then((resp) => {
          this.booking = resp.data;
          this.$router.push({ query: { ...this.$route.query, reload: true } });
          this.showModal = false;
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
      return axios
        .get(`/api/mrbs_booking/${id}?with=attendees`)
        .then((resp) => {
          this.booking = resp.data;

          let attendeesArray = [];

          this.booking.attendees.forEach((attendee) => {
            attendeesArray.push(attendee.id);
          });

          this.booking.attendee_id = attendeesArray;
        });
    },

    update() {
      this.booking.start_datetime = this.booking.start_datetime
        ? format(new Date(this.booking.start_datetime), "yyyy-MM-dd hh:mm:ss")
        : "";
      this.booking.end_datetime = this.booking.end_datetime
        ? format(new Date(this.booking.end_datetime), "yyyy-MM-d HH:mm:ss")
        : "";

      return axios
        .put(`/api/booking/update/${this.booking.id}`, this.booking)
        .then((resp) => {
          this.booking = resp.data;
          this.$router.push({ query: { ...this.$route.query, reload: true } });
          this.showModal = false;
        });
    },

    remove(id) {
      this.$confirm.require({
        target: event.currentTarget,
        message: "Do you want to delete this record?",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: () => {
          return axios.delete(`/api/mrbs_booking/${id}`).then((resp) => {
            this.$router.push({
              query: { ...this.$route.query, reload: true },
            });
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

    getRoomList() {
      return axios.get(`/api/mrbs_rooms`).then((response) => {
        this.roomList = response.data;
        console.log(this.typeList);
      });
    },

    getAttendeeList() {
      return axios.get(`/api/mrbs_attendees`).then((response) => {
        this.attendeeList = response.data;
        console.log(this.typeList);
      });
    },
  },
  mounted() {
    this.getRoomList();
    this.getAttendeeList();
  },
};
</script>

<style >
.p-dialog-content {
  overflow-y: visible !important;
}
</style>