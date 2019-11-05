import axios from 'axios';

const api = axios.create({
  baseURL: 'http://cashme.lndo.site:8000/',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
});

export default api;
