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
                const res = await axios.get("/api/logo-school");
                this.imageSrc = "/storage/" + res.data.configuration.logo;
                this.imageLoaded = true;
            } catch (error) {
                console.error("Error fetching image:", error);
            }
        },
    },
});
