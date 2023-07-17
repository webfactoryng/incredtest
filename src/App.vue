<template>
  <div>
    <PageHeader />
    <ProductList />
    <PageFooter />
  </div>
</template>

<script>
import PageHeader from "./components/PageHeader.vue";
import ProductList from "./components/ProductList.vue";
import PageFooter from "./components/PageFooter.vue";

export default {
  components: {
    PageHeader,
    ProductList,
    PageFooter,
  },
  data() {
    return {
      showAddProductForm: false, // Control whether to show the add product form
    };
  },
  methods: {
    toggleAddProductForm() {
      this.showAddProductForm = !this.showAddProductForm;
    },
  },
};
</script>
