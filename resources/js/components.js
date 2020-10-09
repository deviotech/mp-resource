import Vue from "vue"
import { VueEditor } from "vue2-editor";

import Toasted from 'vue-toasted';
import VueSanitize from "vue-sanitize";
import dropdown from 'vue-dropdowns';

import Profile from "./components/user/profile";
import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';


import HomeAttribute from "./components/admin/attribute/HomeAttribute";
import ShowAttribute from "./components/admin/attribute/ShowAttribute";

import HomeAttribute_value from "./components/admin/attribute_value/HomeAttribute_value";

import HomeBrands from "./components/admin/brand/HomeBrand";


import HomeCategory from "./components/admin/category/HomeCategory";

import HomeProduct from "./components/admin/product/HomeProduct";
import ProductComp from "./components/admin/product/ProductComp";

import HomeUser from "./components/admin/user/HomeUser";

import HomeVariation from "./components/admin/variation/HomeVariation";
import ShowVariation from "./components/admin/variation/ShowVariation";

import HomeVariation_value from "./components/admin/variation_value/HomeVariation_value";

import HomeOrder from "./components/admin/order/HomeOrder";

import Shop from "./components/user/Shop";


import Product from "./components/user/Product";
import Cart from "./components/user/Cart";
import Order from "./components/user/Order";
import vuetify from "./vuetify";

Vue.component('login', Login);
Vue.component('register', Register);

Vue.component('home-attribute', HomeAttribute);
Vue.component('show-attribute', ShowAttribute);

Vue.component('home-brand',HomeBrands);

Vue.component('home-attribute_value', HomeAttribute_value);

Vue.component('home-category', HomeCategory);

Vue.component('home-product', HomeProduct);
Vue.component('product-comp',ProductComp);

Vue.component('home-user', HomeUser);

Vue.component('home-variation', HomeVariation);
Vue.component('show-variation', ShowVariation);

Vue.component('home-variation_value', HomeVariation_value);

Vue.component('home-order', HomeOrder);
Vue.component('profile', Profile);

Vue.component('shop', Shop);

Vue.component('product', Product);
Vue.component('cart', Cart);
Vue.component('order', Order);
Vue.component('vue-editor',VueEditor);

Vue.use(VueSanitize);

Vue.component('',Toasted);
Vue.config.productionTip = false
Vue.use(Toasted, {
  duration: 3000
});
