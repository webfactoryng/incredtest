<template>
  <header class="flex items-center justify-between p-4 header-bg">
    <img src="../assets/logo.png" alt="Logo" class="w-auto h-12" />
    <button
      class="bg-[#ee7b32] text-white px-4 py-2 rounded"
      @click="showAddProductForm"
    >
      <i class="fas fa-plus"></i> Add Product
    </button>
  </header>

  <div>
    <div
      v-if="isAddProductFormVisible"
      class="fixed inset-0 flex items-center justify-center z-50"
    >
      <div
        class="bg-gray-900 bg-opacity-50 absolute inset-0"
        @click="cancelButton"
      ></div>
      <div class="bg-white rounded-md max-w-md mx-auto p-6 z-10">
        <h1 class="text-2xl font-bold mb-6">PRODUCT ENTRY</h1>
        <form @submit.prevent="submitForm">
          <div class="mb-4 text-left">
            <label for="title" class="text-gray-800"
              >Title <span class="text-red-500">*</span></label
            >
            <input
              v-model="product.title"
              type="text"
              id="title"
              placeholder="Title"
              required
              class="w-full px-3 py-2 border rounded-md"
            />
          </div>
          <div class="mb-4 text-left">
            <label for="description" class="text-gray-800"
              >Description <span class="text-red-500">*</span></label
            >
            <textarea
              v-model="product.description"
              id="description"
              placeholder="Description"
              class="w-full px-3 py-2 border rounded-md"
            ></textarea>
          </div>
          <div class="mb-4 text-left">
            <label for="price" class="text-gray-800"
              >Price (CAD) <span class="text-red-500">*</span></label
            >
            <input
              id="price"
              placeholder="Price"
              type="text"
              v-model="product.price"
              @input="formatMoneyInput"
              required
              class="w-full px-3 py-2 border rounded-md"
            />
          </div>
          <div class="flex justify-end">
            <button
              type="submit"
              class="px-4 py-2 text-white bg-blue-500 rounded-md mr-2"
            >
              {{ action === "create" ? "Submit" : "Update" }}
            </button>
            <button
              type="button"
              class="px-4 py-2 text-white bg-gray-500 rounded-md"
              @click="cancelButton"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ProductForm",
  data() {
    return {
      product: {
        title: "",
        description: "",
        price: null,
      },
      action: "create",
      amount: "",
      isAddProductFormVisible: false,
    };
  },
  methods: {
    showAddProductForm() {
      this.isAddProductFormVisible = true;
    },
    async submitForm() {
      try {
        const apiEndpoint = "http://localhost:8888/add/product";
        const { title, description, price } = this.product;

        const formData = new FormData();
        formData.append("title", title);
        formData.append("description", description);
        formData.append("price", price);

        const response = await axios.post(apiEndpoint, formData);
        alert(JSON.stringify(response.data));

        // Clear the input fields
        this.product.title = "";
        this.product.description = "";
        this.product.price = null;

        this.isAddProductFormVisible = false;

        // Reload document (there are better ways to do this. but permit this for this time )
        location.reload();
      } catch (error) {
        console.error(error);
        alert("Error submitting form. Please try again.");
      }
    },
    cancelButton() {
      this.isAddProductFormVisible = false;
    },
    formatMoneyInput(event) {
      let value = event.target.value.replace(/\D/g, "");
      value = (value / 100).toFixed(2);
      value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      this.product.price = value;
    },
  },
};
</script>
