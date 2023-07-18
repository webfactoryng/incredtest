<template>
  <div id="indicator">CSV created! Uploading to server in 5secs...</div>
  <ExportButton @click="exportToCSV" />
  <div class="p-4">
    <table class="min-w-full border border-gray-200 text-left">
      <thead class="bg-gray-800 text-white">
        <tr>
          <th class="border-b py-2">&nbsp;ID</th>
          <th class="border-b py-2">Title</th>
          <th class="border-b py-2">Description</th>
          <th class="border-b py-2">Price (CAD)</th>
          <th class="border-b py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(product, index) in products"
          :key="product.id"
          :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-white'"
        >
          <td class="border-b py-2">&nbsp;{{ product.id }}</td>
          <td class="border-b py-2">
            <span v-if="!isEditing(index)">{{ product.title }}</span>
            <input v-else v-model="editedProducts[index].title" />
          </td>
          <td class="border-b py-2">
            <span v-if="!isEditing(index)">{{ product.description }}</span>
            <input v-else v-model="editedProducts[index].description" />
          </td>
          <td class="border-b py-2">
            <span v-if="!isEditing(index)">{{ product.price }}</span>
            <input v-else v-model="editedProducts[index].price" />
          </td>
          <td class="border-b py-2">
            <button v-if="!isEditing(index)" @click="editProduct(index)">
              Edit
            </button>
            <button v-else @click="saveProduct(index)">Save</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <ExportButton @click="exportToCSV" />

  <p><br /></p>
  <p><br /></p>
  <p><br /></p>
  <p><br /></p>
  <p><br /></p>
</template>

<script>
import ExportButton from "./ExportButton.vue";
import axios from "axios";

export default {
  name: "ProductList",
  components: {
    ExportButton,
  },
  data() {
    return {
      products: [], // hold product data for now
      editedProducts: [],
    };
  },
  // Fetch products data from the API endpoint
  mounted() {
    this.fetchProducts();
  },
  methods: {
    refreshProducts() {
      this.fetchProducts()
        .then(() => {
          console.log("Products refreshed.");
        })
        .catch((error) => {
          console.error("Error refreshing products:", error);
        });
    },
    fetchProducts() {
      axios
        .get("http://localhost:8888/products")
        .then((response) => {
          this.products = response.data;
          this.editedProducts = Array(response.data.length).fill(null);
        })
        .catch((error) => {
          console.error("Error fetching products:", error);
        });
    },
    exportToCSV() {
      const showIndicator = document.getElementById("indicator");
      showIndicator.style.display = "block";
      const rows = this.products.map((product) => [
        product.id,
        product.title,
        product.description,
        product.price,
      ]);

      let csvContent = "ID, TITLE, DESCRIPTION, PRICE\n";
      csvContent += rows.map((row) => row.join(",")).join("\n");

      setTimeout(() => {
        showIndicator.style.display = "none";
        const socket = new WebSocket("ws://localhost:8181/socket/server.php");
        socket.onopen = () => {
          socket.send(csvContent);
          socket.close();
        };
      }, 5000);
    },
    isEditing(index) {
      return this.editedProducts[index] !== null;
    },
    editProduct(index) {
      this.editedProducts[index] = { ...this.products[index] };
    },
    async saveProduct(index) {
      try {
        const apiEndpoint = "http://localhost:8888/update/product";
        const editedProduct = this.editedProducts[index];

        const formData = new FormData();
        Object.entries(editedProduct).forEach(([key, value]) => {
          formData.append(key, value);
        });

        const response = await axios.post(apiEndpoint, formData);

        // Updat the product data in the products array
        this.products[index] = editedProduct;

        // Reset the editedProduct for this row
        this.editedProducts[index] = null;

        // Emit an event to notify other components that the product list has been updated

        console.log(response.data.message);
      } catch (error) {
        console.error(error);
        alert("Error saving data. Please try again.");
      }
    },
  },
};
</script>
