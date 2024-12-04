<template>
  <div class="container">
    <h1 class="title">Busca de Municípios</h1>
    <div class="filters">
      <select v-model="selectedUf" @change="fetchData">
        <option disabled value="">Selecione um estado</option>
        <option v-for="uf in ufs" :key="uf" :value="uf">{{ uf }}</option>
      </select>
      <input 
        type="text" 
        v-model="searchTerm" 
        placeholder="Buscar..." 
        @keyup.enter="fetchData" 
      />
      <button @click="fetchData">Buscar</button>
    </div>
    <table class="results-table">
      <thead>
        <tr>
          <th>Código IBGE</th>
          <th>Município</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="municipio in municipios" :key="municipio.ibge_code">
          <td>{{ municipio.ibge_code }}</td>
          <td>{{ municipio.name }}</td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button :disabled="currentPage === 1" @click="changePage(currentPage - 1)">Anterior</button>
      <span>Página {{ currentPage }} de {{ totalPages }}</span>
      <button :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">Próxima</button>
      <select v-model="itemsPerPage" @change="fetchData">
        <option v-for="size in [5, 10, 15, 20]" :key="size" :value="size">{{ size }} itens</option>
      </select>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      ufs: ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"],
      selectedUf: '',
      searchTerm: '',
      municipios: [],
      currentPage: 1,
      totalPages: 0,
      itemsPerPage: 10,
    };
  },
  methods: {
    async fetchData() {
      if (!this.selectedUf) return alert("Selecione um estado!");
      try {
        const response = await axios.get(`/api/municipios/${this.selectedUf}`, {
          params: {
            search_term: this.searchTerm,
            page: this.currentPage,
            take: this.itemsPerPage,
          },
        });
        this.municipios = response.data.data.data;
        this.totalPages = Math.ceil(response.data.data.total / this.itemsPerPage);
      } catch (error) {
        console.error("Erro ao buscar municípios:", error);
      }
    },
    changePage(page) {
      this.currentPage = page;
      this.fetchData();
    },
  },
};
</script>

<style>
.container {
  font-family: Arial, sans-serif;
  max-width: 800px;
  margin: 20px auto;
}
.title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 20px;
}
.filters {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}
.filters select,
.filters input,
.filters button {
  margin: 5px;
  padding: 10px;
  font-size: 16px;
}
.results-table {
  width: 100%;
  border-collapse: collapse;
}
.results-table th,
.results-table td {
  border: 1px solid #ddd;
  padding: 8px;
}
.results-table th {
  background-color: black;
}
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}
.pagination button,
.pagination select {
  padding: 10px;
  font-size: 16px;
}
</style>
