export default function dateFilter(value, format = 'datetime') {
    const options ={}
    //includes провер содержит ли строка подстроку
    if(format.includes('date')){
        options.day = '2-digit'
        options.month = 'long'
        options.year = 'numeric'

    }

    if(format.includes('time')){
        options.hour = '2-digit'
        options.minute = '2-digit'
        options.second = '2-digit'

    }

    const locale = 'ru-Ru'
    // из документ Intl.DateTimeFormat 1 парам-локаль, 2 - опции
    return new Intl.DateTimeFormat(locale, options).format(new Date(value))
}
