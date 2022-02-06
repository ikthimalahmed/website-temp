<template>
  <div>
    <booking-modal ref="bookingDialog"> </booking-modal>
    <mail-modal ref="mailDialog"> </mail-modal>
    <ConfirmPopup></ConfirmPopup>

    <Toolbar>
      <template #left>
        <Button
          label="NEW"
          icon="pi pi-plus"
          class="p-mr-2 p-button-sm"
          @click="$refs.bookingDialog.new()"
        />
      </template>

    </Toolbar>

    <Card>
      <template #content>
        <table class="table table-striped" style="width: 90%">
          <thead>
            <tr>
              <th>#</th>
              <th>Room Name</th>
              <th>Check-in Date</th>
              <th>Check-out Date</th>
              <th>Description</th>
              <th>Guests</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="index">
              <td class="english">{{ index + 1 }}</td>
              <td>{{ item.room ? item.room.name : "" }}</td>
              <td>{{ item.formatted_start_date }}</td>
              <td>{{ item.formatted_end_date }}</td>
              <td>{{ item.description }}</td>
              <td>
                <span v-for="(staff, index) in item.users" :key="index"
                  ><Tag severity="success" rounded>{{ staff.name }}</Tag
                  >&nbsp;</span
                >
                <span v-for="(attendee, index) in item.attendees" :key="index"
                  ><Tag severity="warning" rounded>{{ attendee.name }}</Tag
                  >&nbsp;</span
                >
              </td>
              <td>
                <i
                  class="pi pi-pencil"
                  @click="$refs.bookingDialog.edit(item.id)"
                  >&nbsp;</i
                >
                <i
                  class="pi pi-trash"
                  @click="$refs.bookingDialog.remove(item.id, $event)"
                  >&nbsp;</i
                >
                <i
                  class="pi pi-inbox"
                  @click="$refs.mailDialog.mailList(item.id)"
                  >&nbsp;</i
                >
              </td>
            </tr>
          </tbody>
        </table>
      </template>
    </Card>
  </div>
</template>

<script>
import BookingModal from "@/components/bookings/BookingModal.vue";
import MailModal from "@/components/mail/MailModal.vue";
import axios from "@/components/infra/axios";
export default {
  data() {
    return {
    items: "",
    };
  },

  components: {
    BookingModal,
    MailModal,
  },

   watch: {
    "$route.params"() {
      if (this.$route.query["reload"]) {
        this.getItems().then(() => {
          this.$router.push({ query: { ...this.$route.query, reload: false } });
        });
      }
    },
  },

  methods: {
    getItems() {
      
      return axios.get("/api/mrbs_booking?with=room,attendees").then((resp) => {
        this.items = resp.data;
      });
    },
  },

  mounted() {
    this.getItems();
    
  },
};
</script>

