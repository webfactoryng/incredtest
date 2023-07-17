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
</template>

<script>
export default {
  name: "PageHeader",
  methods: {
    showAddProductForm() {
      const showDiv = document.getElementById("showDiv");
      showDiv.style.display = "block";
    },
  },
};
</script>
