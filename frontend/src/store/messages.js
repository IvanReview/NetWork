import axios from 'axios'

export default {
    state: {
        dialogs: [],
        messages: [],
        

    },
    actions: {

        async fetchDialogs({commit}, user_id) {
       
            try{
                let dialogs = await axios(`/dialogs/${user_id}`);

                commit('set_dialogs', dialogs.data)
                return dialogs.status
                
            } catch(e){
                console.log(e)   
            }
        },

        async fetchMessagesForDialog({commit}, dialog_id, user_id = 1) {
            try{
                let messages = await axios(`/dialogs/messages/${dialog_id}/${user_id}`);

                commit('set_dialog_messages', messages.data)
                if (messages.data.length) {

                    commit('update_dialogs', messages.data[0].dialog_id) //поменять статус последнего сообщения на прочитанное
                }
                return messages.status

            } catch(e){
                console.log(e)
            }
        },

        async addMessageToDialog({commit}, formData) {
            try{
                let message = await axios({
                    method: 'POST',
                    url: `/dialogs/message/${formData.get('dialog_id')}/${formData.get('user_id')}`,
                    data: formData,
                });

                commit('add_dialog_message', message.data)
                return message.status

            } catch(e){
                console.log(e)
            }
        },

        async delMessageFromDialog({commit}, message_id) {
            try{
                let message = await axios({
                    method: 'DELETE',
                    url: `/dialogs/del-message/${message_id}`,
                });

                commit('del_dialog_message', message_id)
                return message.status

            } catch(e){
                console.log(e)
            }
        },


        async addNewMessageAndNewDialog({commit}, formData) {
            try{
                let message = await axios({
                    method: 'POST',
                    url: `/dialogs/new-message/${formData.get('user_id')}`,
                    data: formData,
                });
                return message.status

            } catch(e){
                console.log(e)
            }
        },




    },
    mutations: {

        set_dialogs(state, dialogs) {
            state.dialogs = dialogs
        },

        set_dialog_messages(state, messages) {
            state.messages = messages
        },

        add_dialog_message(state, message) {
            state.messages.push(message)
        },

        update_dialogs(state, dialog_id) {

            state.dialogs = state.dialogs.map(item => {
                if (item.dialog_id === dialog_id) {
                    item.unread = 0
                }
                return item
            })
        },

        del_dialog_message(state, mess_id) {
             state.messages = state.messages.filter((message) => message.message_id !== mess_id)
        }


    },

    getters: {
        getDialogs(state) {
            return state.dialogs
        },

        getMessages(state) {
            return state.messages
        }

     

    }
}
