import axios from 'axios'

export default {
    state: {
        posts: [],
        post: {},
        

    },
    actions: {

        async fetchPosts({commit}, {offset, userId}) {
       
            try{
                let posts = await axios(`/posts/${userId}`, {
                    params: {offset}
                });

                commit('set_posts', posts.data)
                return posts.status
                
            } catch(e){
                console.log(e)   
            }
        },

        async addPostInBd({commit}, {formData, userId, author_id=2}) {
            try{
                let post = await axios({
                    method: 'POST',
                    url: `/post/${userId}/${author_id}`,
                    data: formData,
                });

                commit('set_new_post', post.data)
                return post.status

            } catch(e){
                console.log(e)
            }
        },

        async editPostInBd({commit}, {formData, post_id}) {
            try{
                let post = await axios({
                    method: 'POST',
                    url: `/post-edit/${post_id}`,
                    data: formData,
                });

                commit('edit_post', post.data)
                return post.status

            } catch(e){
                console.log(e)
            }
        },

        async deletePostFromBd({commit}, formData){
            try{
                let response = await axios({
                    method: 'DELETE',
                    url: `/post_del/${formData.get('post_id')}`,
                    data: formData,
                });

                commit('delete_post', response.data.post_id)

                return response.status

            } catch(e){
                console.log(e)
            }
        },


        async deleteCommentFromBd({commit}, formData){
            try{
                let response = await axios({
                    method: 'DELETE',
                    url: `/comment-delete/${formData.get('comment_id')}`,
                    data: formData,
                });

                commit('delete_comment', {comment_id: response.data.comment_id, post_id: formData.get('post_id')})

                return response.status

            } catch(e){
                console.log(e)
            }
        },


        async addCommentToPost({commit}, {formData, user_id}){
            try{
                let comment = await axios({
                    method: 'POST',
                    url: `/comments/${formData.get('post_id')}/${user_id}`,
                    data: formData,
                });

                commit('set_new_comment', comment.data)

                return comment.status

            } catch(e){
                console.log(e)
            }
        },


        async addLikeToPost({commit}, formData){
            try{
                let postNew = await axios({
                    method: 'POST',
                    url: `/likes/${formData.get('post_id')}/${formData.get('user_id')}`,
                    data: formData,
                });

                commit('set_new_like_or_dis', postNew.data)

                return postNew.status

            } catch(e){
                console.log(e)
            }
        }


    },
    mutations: {

        set_posts(state, posts) {

            state.posts = state.posts.concat(posts)
        },

        set_new_post(state, post) {

            state.posts.unshift(post)
        },

        edit_post(state, post) {
            let index = state.posts.findIndex(p => p.id ===post.id)

            state.posts.splice(index, 1, post )
        },

        set_new_comment(state, comment) {
            let index = state.posts.findIndex((item) => item.id === comment.post_id)

            if (state.posts[index]['comments'])  state.posts[index]['comments'].push(comment)
            else state.posts[index]['comments'] = [comment]

        },

        set_new_like_or_dis(state, postNew){
            state.posts = state.posts.map(post => {
                if (post.id === postNew.id){
                    post.likes_count  = postNew.likes_count
                    post.dislikes_count = postNew.dislikes_count
                }
                return post
            })
        },

        post_reset(state,) {
            state.posts = []
        },

        delete_post(state, post_id) {
            state.posts = state.posts.filter(post => post.id !== post_id)
        },

        delete_comment(state, {comment_id, post_id}){

            let post_index = state.posts.findIndex(post => post.id === post_id)
            let comment_index = state.posts[post_index]['comments'].findIndex(com => com.id === comment_id)

            state.posts[post_index]['comments'].splice(comment_index, 1)
        }


        /*
        update_users_to_state(state, {user, index}) {
            state.users.splice(index, 1, user)
        },

        profile_update_errors(state, errors) {
            state.userError = errors
        }*/


    },

    getters: {
        getPosts(state) {
            return state.posts
        },

     

    }
}
