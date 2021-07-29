function is_numeric(data){

    return  !isNaN(data)
}


export  function makePagination(data) {
    //засовываем параметры пагинации в объект, который будет перезаписан, каждый раз при вызов метода

    const pagination = {

        current_page: data.currentPage,
        last_page: data.pageCount,
        next_page_url: data.next_page_url,
        prev_page_url: data.prev_page_url,
        pages: data.links,
    };

    return pagination
}
