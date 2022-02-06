<template>
  <div>
    <room-modal ref="roomDialog"> </room-modal>
    <ConfirmPopup></ConfirmPopup>
    <Toolbar>
      <template #left>
        <Button
          label="NEW"
          icon="pi pi-plus"
          class="p-mr-2 p-button-sm"
          @click="$refs.roomDialog.new()"
        />
      </template>

    </Toolbar>

    <Card>
      <template #content>
        <table class="table table-striped" style="width: 50%">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="index">
              <td class="english">{{ index + 1 }}</td>
              <td>{{ item.name }}</td>
              <td>
                <i
                  class="pi pi-pencil"
                  @click="$refs.roomDialog.edit(item.id)">&nbsp;</i>
                <i
                  class="pi pi-trash"
                  @click="$refs.roomDialog.remove(item.id, $event)">&nbsp;</i>
              </td>
            </tr>
          </tbody>
        </table>
      </template>
    ></Card>
  </div>
</template>

<script>
import axios from "@/components/infra/axios";
import roomModal from "./TestingModal.vue";
export default {
  data() {
    return {
      items: "",
    };
  },

  components: {
    roomModal,
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
      return axios.get("/api/mrbs_rooms").then((resp) => {
        this.items = resp.data;
      });
    },
  },

  mounted() {
    this.getItems();
  },
};
</script>