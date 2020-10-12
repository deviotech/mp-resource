<template>
  <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Email</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="list in lists" :key="list.id">
          <th scope="row">{{ list.id }}</th>
          <td>{{ list.email }}</td>
          <td>
            <v-btn class="btn btn-info">Send Email</v-btn>
          </td>
        </tr>
      </tbody>
    </table>
    <form>
      <div class="form-group">
        <label for="exampleFormControlSelect2">Multiple select</label>
        <select multiple class="form-control" id="exampleFormControlSelect2">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1">Subject</label>
        <input
          type="text"
          class="form-control"
          id="exampleFormControlInput1"
          placeholder="name@example.com"
        />
      </div>

      <v-col cols="12">
        <vue-editor v-model="description" label="Description"></vue-editor>
      </v-col>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      lists: [],
      description: "",
    };
  },
  mounted() {
    this.initialize();
  },
  methods: {
    initialize() {
      axios
        .get("/admin/newsletter_list")
        .then((response) => {
          this.lists = response.data.data;
        })
        .catch((error) => {
          console.log(error.message);
        });
    },
  },
};
</script>

<style>
</style>