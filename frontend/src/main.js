import "./assets/main.css";

import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "./App.vue";
import router from "./router";

// import express from "express";
// import cors from "cors";
// const app = express();
// app.use(cors());

const app = createApp(App);

app.use(createPinia());
app.use(router);

app.mount("#app");
