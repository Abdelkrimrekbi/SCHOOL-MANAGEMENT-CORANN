// store.js
import { defineStore } from 'pinia';

export const useImageStore = defineStore({
  id: 'image',
  state: () => ({
    imageSrc: '',
    imageLoaded: false,
  }),
  actions: {
    async loadImage() {
      try {
        const res = await axios.get("/api/config");
       // console.log("hna kywsel lih store jsssssssssssssssss",res.data);
        this.imageSrc = "/storage/" + res.data.configuration.logo;
       // console.log("imageSrc :", this.imageSrc);
        this.imageLoaded = true;
      } catch (error) {
       // console.error("Error fetching image:", error);
      }
    },
  },
});
