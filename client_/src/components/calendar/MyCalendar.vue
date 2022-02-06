<template>
  <div>

    <FullCalendar :events="events" :options="options" />
  </div>
</template>

<script>
import axios from "@/components/infra/axios";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import listPlugin from "@fullcalendar/list";
export default {
  data() {
    return {
      showModal: false,
      events: [],

      options: {
        
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        headerToolbar: {
          left: "prev,next",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
        },
      },
    };
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
      return axios.get("/api/booking/getMyCalendar").then((resp) => {
        this.events = resp.data;
      });
    },

    closeModal() {
      this.$router.push({ query: { ...this.$route.query, reload: true } });
      this.showModal = false;
    },


  },

  created() {
    this.getItems();
  },
};
</script>