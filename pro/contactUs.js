const closebtn=document.querySelector("#closebtn");
const menubtn=document.querySelector("#menubtn");
const nav=document.querySelector("#nav_row");



const column_nav_disp=()=>{
    menubtn.classList.remove("max-md:opacity-100");
    closebtn.classList.replace("opacity-0","opacity-100");
    setTimeout(()=>{
        menubtn.classList.remove("max-md:block");
        closebtn.classList.remove("hidden");
        nav.classList.remove("max-md:opacity-0", "max-md:pointer-events-none");
        nav.classList.add("max-md:opacity-100", "max-md:pointer-events-auto");
    },200);
   
}

const column_nav_hide=()=>{
    menubtn.classList.add("max-md:opacity-100");
    closebtn.classList.replace("opacity-100","opacity-0");

    nav.classList.remove("max-md:opacity-100", "max-md:pointer-events-auto");
    nav.classList.add("max-md:opacity-0", "max-md:pointer-events-none");

    setTimeout(()=>{
        closebtn.classList.add("hidden");
        menubtn.classList.add("max-md:block");
    },200);
}

window.addEventListener("resize",()=>{

    if(window.innerWidth >= 768)
    {
        closebtn.classList.add("hidden");
        menubtn.classList.add("max-md:block");
        closebtn.classList.add("opacity-0");
        menubtn.classList.add("max-md:opacity-100")

        nav.classList.remove("max-md:opacity-100", "max-md:pointer-events-auto");
        nav.classList.add("max-md:opacity-0", "max-md:pointer-events-none");
    }
});


document.addEventListener('DOMContentLoaded', ()=>{
    const elements = document.querySelectorAll('.reveal-on-scroll');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) 
            {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    },{
        threshold: 0.3 
    });

    elements.forEach(element => {
        observer.observe(element);
    });
});


var lt=31.2161872;
var lg=75.771881;

var outLat=31.2158858;
var outLong=75.758852;

// Initialize the Leaflet map
var map = L.map('location').setView([lt, lg], 15); // Zoom level 15

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Add a marker with a clickable popup
L.marker([lt, lg])
    .addTo(map)
    .bindPopup('<b>Sam Homeopathic Clinic</b><br><a target="main" href="https://www.google.com/maps/place/Sam+Homoeopathic+Clinic/@31.216187,75.7719008,17z/data=!3m1!4b1!4m6!3m5!1s0x391af4dd2ca3727b:0x13b20e35734a3154!8m2!3d31.216187!4d75.7719008!16s%2Fg%2F11c6v1mqfn?entry=ttu&g_ep=EgoyMDI1MDMyNS4xIKXMDSoASAFQAw%3D%3D">Open Map</a>')
    .openPopup();
