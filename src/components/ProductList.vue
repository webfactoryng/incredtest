<template>
  <div id="indicator">CSV created! Uploading to server in 5secs...</div>
  <ProductForm />
  <ExportButton @click="exportToCSV" />
  <div class="p-4">
    <table class="min-w-full border border-gray-200 text-left">
      <thead class="bg-gray-800 text-white">
        <tr>
          <th class="border-b py-2">&nbsp;ID</th>
          <th class="border-b py-2">Title</th>
          <th class="border-b py-2">Description</th>
          <th class="border-b py-2">Price (CAD)</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(product, index) in products"
          :key="product.id"
          :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-white'"
        >
          <td class="border-b py-2">&nbsp;{{ product.id }}</td>
          <td class="border-b py-2">{{ product.title }}</td>
          <td class="border-b py-2">{{ product.description }}</td>
          <td class="border-b py-2">{{ product.price }}</td>
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
import ProductForm from "./ProductForm.vue";

export default {
  name: "ProductList",
  components: {
    ExportButton,
    ProductForm,
  },
  data() {
    return {
      products: [], // hold product data for now
      isFormShowing: true,
    };
  },
  // Fetch products data from the API endpoint
  mounted() {
    fetch("http://localhost:8888/products")
      .then((response) => response.json())
      .then((data) => (this.products = data));
  },
  methods: {
    exportToCSV() {
      const showIndicator = document.getElementById("indicator");
      showIndicator.style.display = "block";
      const rows = this.products.map((product) => [
        product.id,
        product.title,
        product.description,
        product.price,
      ]);

      let csvContent = "data:text/csv;charset=utf-8,";
      csvContent += rows.map((row) => row.join(",")).join("\n");

      const encodedURI = encodeURI(csvContent);

      //Delay for 5 seconds
      setTimeout(() => {
        showIndicator.style.display = "none";
        // alert(encodedURI);
        // Usin WebSocket to send the file to the server
        const socket = new WebSocket("ws://localhost:8181/socket/socket.php");
        socket.onopen = () => {
          socket.send(encodedURI);
          socket.close();
        };
      }, 5000);
    },
  },
};
</script>
