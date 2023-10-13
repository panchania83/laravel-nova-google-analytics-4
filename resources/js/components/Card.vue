<template>
  <card class="px-4 py-4">
    <div class="mb-1">
      <h5 class="mr-3 text-base text-80 font-bold">
        GA Most-visited pages this week
      </h5>
    </div>
    <div v-if="!pages" class="flex items-center">
      <p class="text-80 font-bold">No Data</p>
    </div>
    <ul v-else class="most-visited-pages-list mb-4 mt-2 overflow-y-scroll">
      <li v-for="pagedata in pages">
        <a
          :href="`https://${pagedata.hostname}${pagedata.pagepath}`"
          target="_blank"
          >{{ pagedata.pagetitle }}</a
        >: {{ pagedata.totalusers }}
      </li>
    </ul>
  </card>
</template>

<script>
export default {
  props: ["card"],

  data: function () {
    return {
      pages: [],
    };
  },
  mounted() {
    Nova.request()
      .get("/api/ga4-most-visited-pages")
      .then((response) => {
        this.pages = response.data;
      })
      .catch((error) => {
        console.error(error);
      });
  },
};
</script>

<style scoped>
.most-visited-pages-list {
  height: 6rem;
  font-size: 13px;
}
</style>