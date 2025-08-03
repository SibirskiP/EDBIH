import './bootstrap';


// Echo.channel('chat.1')  // Pretplati se na kanal chat.1
//     .listen('MessageSent', (event) => {  // Slušaj za događaj MessageSent
//         console.log('Nova poruka:', event.message);
//     });
//

Echo.channel('messages2').listen(

    'MessageSent2',(e)=>{
        console.log(e);
    }
)
