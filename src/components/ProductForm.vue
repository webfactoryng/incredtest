<template>
  <div class="prod-entry" id="showDiv">
    <h1 class="pentry">PRODUCT ENTRY</h1>
    <form @submit.prevent="submitForm" class="max-w-md mx-auto">
      <div class="mb-4">
        <div class="text-white text-left">
          Title <span class="caution-text">*</span>
        </div>
        <input
          v-model="product.title"
          type="text"
          placeholder="Title"
          required
          class="w-full px-3 py-2 border rounded-md"
        />
      </div>
      <div class="mb-4">
        <div class="text-white text-left">
          Description <span class="caution-text">*</span>
        </div>
        <textarea
          v-model="product.description"
          placeholder="Description"
          class="w-full px-3 py-2 border rounded-md"
        ></textarea>
      </div>
      <div class="mb-4">
        <div class="text-white text-left">
          Price (CAD) <span class="caution-text">*</span>
        </div>
        <input
          placeholder="Price"
          type="number"
          v-model="product.price"
          @input="formatMoneyInput"
          required
          class="w-full px-3 py-2 border rounded-md"
        />
      </div>
      <div>
        <button type="submit" class="px-4 py-2 text-white button-bg rounded-md">
          {{ action === "create" ? "Submit" : "Update" }}
        </button>

        <button
          id="cancel"
          type="button"
          class="px-4 py-2 text-white rounded-md"
          @click="CancelButton"
        >
          Cancel
        </button>
      </div>
    </form>
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
    };
  },
  methods: {
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
      } catch (error) {
        console.error(error);
        alert("Error submitting form. Please try again.");
      }
    },
    CancelButton() {
      window.location.reload();
    },
    formatMoneyInput(event) {
      let value = event.target.value.replace(/\D/g, "");
      value = (value / 100).toFixed(2);
      value = value.replace(/\B(?=(\d{4})+(?!\d))/g, ",");
      this.product.price = value;
    },
  },
};
</script>
