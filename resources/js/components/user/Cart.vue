<template>
  <div>
    <div>
      <v-container>
        <h2 class="mb-3">You Have {{ items.length }} Items In Your Cart</h2>
        <v-data-table
          :headers="headers"
          :items="items"
          :items-per-page="15"
          class="elevation-1"
        >
          <template v-slot:item.price="{ item }"
            >{{ item.product.price }} €</template
          >
          <template v-slot:item.total="{ item }"
            >{{ item.quantity * item.product.price }} €</template
          >
          <template v-slot:item.actions="{ item }">
            <v-icon small @click="removeItem(item)"> mdi-delete </v-icon>
          </template>
          <template v-slot:no-data> Cart is empty </template>
          <template v-slot:footer>
            <v-row align="center" justify="center" class="text-center">
              <!-- <v-col cols="12" md="6" order="2" order-md="1">
                <v-btn @click="makeOrder()" :disabled="!hasItems"
                  >Vorbestellen</v-btn
                >
              </v-col> -->
              <!-- <v-col cols="12" md="6" order="1" order-md="2">
                <p class="mb-0">Total price: {{ totalPrice }} €</p>
              </v-col> -->
            </v-row>
          </template>
        </v-data-table>
      </v-container>
    </div>
    <div class="container row">
      <div class="col-md-8"></div>
      <div class="col-md-4">
        <h1>Shopping cart total</h1>
        <hr />
        <table class="table table-borderless" style="border-spacing: 10px">
          <tbody>
            <tr>
              <th>{{ this.headers[4].text }}</th>
              <td>{{ this.totalPrice }}€</td>
            </tr>
          </tbody>
        </table>
        <div>
          <v-text-field
            name="input-7-1"
            filled
            label="Read First"
            auto-grow
            value="Please Read First."
          ></v-text-field>
          <div class="ml-1 row">
            <v-checkbox
              v-model="checkbox"
              :label="checkbox_value"
            ></v-checkbox>
             <v-btn
            @click="makeOrder()"
            :disabled="!hasItems"
            class="btn btn-default float-right mt-3 mb-3"
            >Pre Order</v-btn
          >
          </div>
        </div>
      </div>
    </div>
    <br />
  </div>
</template>

<script>
export default {
  name: "Cart",

  data() {
    return {
      headers: [
        {
          text: "Product Name",
          value: "product.name",
          sortable: false,
        },
        {
          text: "variation_value.name",
          value: "variation_value.name",
          sortable: false,
        },
        {
          text: "Quantity",
          value: "quantity",
          sortable: false,
        },
        {
          text: "Price",
          value: "price",
          sortable: false,
        },
        {
          text: "Total Price",
          value: "total",
          sortable: false,
        },
        {
          text: "Options",
          value: "actions",
          sortable: false,
        },
      ],
      items: [],
      product_detail: [],
    };
  },

  computed: {
    totalPrice() {
      let total_total = 0;

      this.items.forEach((item) => {
        total_total += item.quantity * item.product.price;
      });

      return total_total;
    },

    cartIds() {
      return this.items.map((item) => {
        return item.id;
      });
    },

    hasItems() {
      return this.items[0].quantity > 0;
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      axios
        .get("/back/in-cart")
        .then((response) => {
          console.log(response.data.data.product_id);
          this.items = response.data.data;
          this.product_detail = response.data.data.map((item) => {
            return (item = {
              product_id: item.product_id,
              quantity: item.quantity,
            });
          });
        })
        .catch((error) => {
          console.log(error.message);
        });
    },

    removeItem(item) {
      axios
        .delete("/back/remove-from-cart/" + item.id)
        .then((response) => {
          this.initialize();
          this.count = response.data;
          document.getElementById("cart-count").innerHTML = this.count;
          this.initialize();
        })
        .catch((error) => {
          console.log(error.message);
        });
    },

    formData() {
      return {
        carts_id: this.cartIds,
        total_price: this.totalPrice,
        product_id: this.product_detail,
      };
    },

    makeOrder() {
      axios
        .post("/back/make-order", this.formData())
        .then((response) => {
          console.log(response.data);
          this.initialize();
          this.$toasted.show(response.data);
          window.location.href = "/vorbestellungen/my-pre-orders";
        })
        .catch((error) => {
          console.log(error.message);
        });
    },
  },
};
</script>
