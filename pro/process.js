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


document.addEventListener('DOMContentLoaded', function() {
    const listItems = document.querySelectorAll('.reveal-list li');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add delay based on index for staggered appearance
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 700); // 150ms delay between items
                
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px' // Trigger slightly before reaching the element
    });

    listItems.forEach(item => {
        observer.observe(item);
    });
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