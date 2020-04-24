<template>
  <div>
    <hr class="mb-1 mt-1" />
    <ul class="pl-1 list-unstyled">
      <li v-for="(file, index) in files.filenames" :key="index">
        <small
          ><a href="" @click.prevent="downloadFile(file.name)">
            {{ file.name }}
          </a></small
        >
        <i
          class="fas fa-times float-right text-danger pt-1"
          style="cursor: pointer;"
          @click="removeSavedFile(file.name)"
        >
        </i>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  methods: {
    downloadFile(name) {
      let folder = this.files.path;
      axios
        .get("api/file/" + folder + "/" + name, {
          responseType: "blob"
        })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", name);
          document.body.appendChild(link);
          link.click();
        });
    },
    removeSavedFile(name) {
      Fire.$emit("removeFile", this.id, name);
      // axios
      //   .put("api/todo/file/" + this.id +  "/" + name)
      //   .then(() => Fire.$emit("removeFile", this.id, name));
    }
  },
  props: ["files", "id"]
};
</script>
