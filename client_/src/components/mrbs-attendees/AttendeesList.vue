<template>
  <div>
    <attendees-modal ref="attendeeDialog"> </attendees-modal>
    <ConfirmPopup></ConfirmPopup>
    <Toolbar>
      <template #left>
        <Button
          label="NEW"
          icon="pi pi-plus"
          class="p-mr-2 p-button-sm"
          @click="$refs.attendeeDialog.new()"
        />
      </template>

    </Toolbar>

    <Card>
      <template #content>
        <table class="table table-striped" style="width: 80%">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Mobile</th>
              <th>Email</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="index">
              <td class="english">{{ index + 1 }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.mobile_no }}</td>
              <td>{{ item.email }}</td>
              <td>
                <i
                  class="pi pi-pencil"
                  @click="$refs.attendeeDialog.edit(item.id)">&nbsp;</i>
                <i
                  class="pi pi-trash"
                  @click="$refs.attendeeDialog.remove(item.id, $event)">&nbsp;</i>
              </td>
            </tr>
          </tbody>
        </table>
      </template>
    ></Card>
  </div>
</template>

<script>
import AttendeesModal from "./AttendeesModal.vue";
import axios from "@/components/infra/axios";
export default {
  data() {
    return {
      items: "",
    };
  },

  components: {
    AttendeesModal,
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
      return axios.get("/api/mrbs_attendees").then((resp) => {
        this.items = resp.data;
      });
    },
  },

  mounted() {
    this.getItems();
  },
};
</script>