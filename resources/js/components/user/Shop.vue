<template>
  <div>
    <div class="container">
      <h1>Active ingredients</h1>
      <br />
      <h3>Brands</h3>
      <div class="row ml-1 mt-1">
        <button class="mr-5 mb-5 filter_button" @click="reset()"
          >Reset filter</button>
        <div v-for="brand in brands" :key="brand.id">
          <button class="mr-5 mb-5 filter_button" @click="filterproduct(brand.value)">{{
            brand.text
          }}</button>
        </div>
      </div>
      <br />
      <h3>Categories</h3>
      <div class="row ml-1 mt-1">
        <div v-for="category in categories" :key="category.id">
          <button class="mr-5 mb-5 filter_button" @click="category_filter(category.value)">{{
            category.text
          }}</button>
        </div>
      </div>
      <br />
      <h3>Variation</h3>
      <div class="row ml-1 mt-1">
        <div v-for="variation in viariations" :key="variation.id">
          <button class="mr-5 mb-5 filter_button" @click="variation_filter(variation.value)">{{
            variation.text
          }}</button>
        </div>
      </div>
      <br />
      <div class="row">
        <v-col cols="12" sm="3" md="3">
          <v-select
            v-model="filter_passgin_value"
            :items="sorting"
            @change="sorting_filter()"
            name="sorting"
            label="Sort By Standard Sort"
            outlined
            dense
          ></v-select>
        </v-col>
        <v-col cols="12" sm="3" md="3">
          <v-select
            v-model="view_passing_value"
            :items="view"
            @change="view_filter()"
            name="view"
            label="View Products"
            outlined
            dense
          ></v-select>
        </v-col>
      </div>
      <br />
      <v-row>
        <v-col cols="12" sm="6" md="3" v-for="item in items" :key="item.id">
          <div class="mx-auto card" max-width="400">
            <v-img
              class="black--text align-end"
              height="350px"
              :src="item.image">
              <a :href="'/shop/product/' + item.id"
                ><v-card-title>{{ item.name }}</v-card-title></a>
            </v-img>

            <v-card-actions>
              <v-row justify="center" class="pr-3">
                <a text :href="'/shop/product/' + item.id"> Details </a>
              </v-row>
            </v-card-actions>
          </div>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
export default {
  name: "Shop",

  data() {
    return {
      items: [],
      brands: [],
      multiselect:[
        {
          category_id:null,
          brand_id:null,       
          variation_id:null
        },
      ],
      categories: [],
      viariations: [],
      sorting_element: "",
      view_passing_value: "",
      sorting_array: [],
      filter_passgin_value: "",
      sortfilterdata: [
        {
          brand_id: "",
          category_id: "",
          variation_id: "",
        },
      ],
      view: [
        {
          text: "View 16 Products",
          value: 1,
        },
        {
          text: "View 32 Products",
          value: 2,
        },
        {
          text: "View 48 Products",
          value: 3,
        },
      ],
      sorting: [
        {
          text: "Sort By Name",
          value: 1,
        },
        {
          text: "Sort By Price",
          value: 2,
        },
        {
          text: "Sort By Date",
          value: 3,
        },
        {
          text: "Sort By Popularity",
          value: 4,
        },
        {
          text: "Sort By Ascending Order",
          value: 5,
        },
        {
          text: "Sort By Descending Order",
          value: 6,
        },
      ],
    };
  },

  created() {
    this.initialize();
    this.brandfil();
    this.sorting_filter();
    this.categories_list();
    this.variation_list();
  },

  methods: {
    initialize() {
      axios
        .get("/back/shop")
        .then((response) => {
          this.items = response.data;
        })
        .catch((error) => {
          console.log(error.message);
        });
    },
    brandfil() {
      axios.get("/back/brandfilter").then((response) => {
        this.brands = response.data.data.map((item) => {
          return (item = {
            text: item.name,
            value: item.id,
          });
        });
      });
    },
    variation_list() {
      axios.get("/back/variation_list").then((response) => {
        this.viariations = response.data.data.map((item) => {
          return (item = {
            text: item.name,
            value: item.id,
          });
        });
      });
    },
    categories_list() {
      axios.get("/back/categories_filter").then((response) => {
        this.categories = response.data.data.map((item) => {
          return (item = {
            text: item.name,
            value: item.id,
          });
        });
      });
    },
    filterproduct($value) {
      axios.get(`/back/filterproduct/${$value}`).then((response) => {
        this.multiselect[0].brand_id= $value;
        this.sortfilterdata[0].brand_id = $value;
        this.items = response.data.data;
        //console.log(this.items);
      });
    },
    category_filter($value) {
      axios.get(`/back/category_filter/${$value}`).then((response) => {
        this.multiselect[0].category_id = $value;
        this.sortfilterdata[0].category_id = $value;
        this.items = response.data.data;
        //console.log(this.items);
      });
    },
    variation_filter($value) {
      axios.get(`/back/variation_filter/${$value}`).then((response) => {
        this.multiselect[0].variation_id= $value;
        this.sortfilterdata[0].variation_id = $value;
        this.items = response.data.data;
        //console.log(this.items);
      });
    },
    reset() {
      this.initialize();
    },
    sorting_filter() {
      axios
        .get(`/back/sorting/${this.filter_passgin_value}`)
        .then((response) => {
          this.items = response.data.data;
        });
    },
    view_filter() {
      axios
        .get(`/back/views_sorting/${this.view_passing_value}`)
        .then((response) => {
          this.items = response.data.data;
        });
    },
  },
};
</script>
<style>

.card {
  border-radius: 4px;
  background: #fff;
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.08), 0 0 6px rgba(0, 0, 0, 0.05);
  transition: 0.3s transform cubic-bezier(0.155, 1.105, 0.295, 1.12),
    0.3s box-shadow,
    0.3s -webkit-transform cubic-bezier(0.155, 1.105, 0.295, 1.12);
  cursor: pointer;
}
.aa {
  background: red !important;
}
.card:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
}

.v-card__title {
  color: black;
}
.link {
  color: black;
}
a {
  color: black !important;
}
.filter_button {
      padding: 22px 28px 22px 28px;
    border: 2px solid #ccc;
    background: transparent;
    color: #c1c1c1;
    min-width: 200px;
    height: 30px;
    border-radius: 10px;
    margin-top: 10px;
    margin-right: 14px;
    line-height: 0em;
    text-align: center;
    font-size: 13px;
    font-family: ubuntu;
    font-weight: bold;
    color: #757575;
}
.filter_button:focus{
  border:5px solid green;
}
</style>
