<template>
  <img
    alt="IncrediTest"
    src="./assets/logo.png"
    class="w-48 mx-auto block mb-10"
  />
  <ProductForm />
  <ProductList />
</template>

<script>
import ProductForm from "./components/ProductForm.vue";
import ProductList from "./components/ProductList.vue";

export default {
  name: "App",
  components: {
    ProductForm,
    ProductList,
  },
};
</script>
