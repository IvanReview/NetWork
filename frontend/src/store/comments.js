import axios from 'axios'

export default {
    state: {
        comments: [],


    },
    actions: {

        async fetchCommentsForImage({commit}, user_id) {

            try{
                let comments = await axios(`/images-comment/${user_id}`);

                commit('set_comments_for_image', comments.data)
                return comments.status

            } catch(e){
                console.log(e)
            }
        },

        async addCommentToImage({commit}, formData) {

            try{
                let comments = await axios({
                    method: 'POST',
                    url: `/comment-images-add/${formData.get('image_id')}/${formData.get('author_id')}`,
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }});


                commit('add_comment_for_image', comments.data)
                return comments.status

            } catch(e){
                console.log(e)
            }
        },


    },
    mutations: {

        set_comments_for_image(state, comments) {
            state.comments = comments
        },

        add_comment_for_image(state, comment) {
            state.comments.push(comment)
        }



    },

    getters: {
        getComments(state) {
            return state.comments
        },



    }
}