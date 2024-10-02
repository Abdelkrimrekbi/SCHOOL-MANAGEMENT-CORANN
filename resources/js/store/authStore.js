import { defineStore } from 'pinia'

const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            // all these properties will have their type inferred automatically
            user: {},
            token: null
        }
    },

    getters: {
        getUser: (state) => state.user,
        role: (state) => state.user.role,
        permissions: (state) => state.user.permissions,
        getToken: (state) => state.token,
    },

    actions: {
        setUser(user) {
            console.log('user: ', user)
            this.$patch({ user })
        },
        setToken(token) {
            console.log('token: ', token);
            this.$patch({ token });
        },
    },
});

export default useAuthStore;