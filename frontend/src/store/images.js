import axios from 'axios'

export default {
    state: {
        images: [],
        image: {},


    },
    actions: {

        async fetchImages({commit}, user_id) {

            try{
                let images = await axios(`/images/${user_id}`);

                commit('set_images', images.data)
                return images.status

            } catch(e){
                console.log(e)
            }
        },

        async editImageDesc({commit}, formData) {

            try{
                let image = await axios({
                    method: 'POST',
                    url: `/images-edit/${formData.get('image_id')}`,
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }});

                commit('edit_images', image.data)
                commit('edit_image', image.data)
                return image

            } catch(e){
                console.log(e)
            }
        },

        async addImagesInBd({commit}, formData) {

            try{
                let images = await axios({
                    method: 'POST',
                    url: `/images/${formData.get('user_id')}`,
                    data: formData,
                    headers: {
                    'Content-Type': 'multipart/form-data'
                }});

                commit('set_images', images.data)
                return images.status

            } catch(e){
                console.log(e)
            }
        },

        async deleteImage({commit}, image_id) {
            try{
                let response = await axios({
                    method: 'DELETE',
                    url: `/images/${image_id}`,
                });

                commit('delete_image', image_id)

                return response.status

            } catch(e){
                console.log(e)
            }
        }




    },
    mutations: {

        set_images(state, images) {
            state.images = images
        },

        delete_image(state, image_id) {
            state.images = state.images.filter(image => image.id !== image_id)
        },

        edit_images(state, new_image) {
            let index = state.images.findIndex(image => image.id === new_image.id)
            state.images.splice(index, 1, new_image)
        },

        edit_image(state, new_image) {
            state.image = new_image
        }


    },

    getters: {
        getImages(state) {
            return state.images
        },

        getImage(state) {
            return state.image
        },


    }
}