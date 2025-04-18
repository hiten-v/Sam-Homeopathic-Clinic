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




document.addEventListener('DOMContentLoaded', function() {
    const workDivs = [
        document.querySelector('#work_div1'),
        document.querySelector('#work_div2'),
        document.querySelector('#work_div3'),
        document.querySelector('#work_div4')
    ];
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (window.innerWidth <= 768) 
                {
                    // Mobile behavior - add revealed class
                    entry.target.classList.add('revealed');
                } 
                else 
                {
                    // Desktop behavior - trigger appropriate animation
                    const id = entry.target.id;
                    
                    // Reset animation and trigger reflow
                    entry.target.style.animation = 'none';
                    void entry.target.offsetWidth;
                    
                    // Apply new animation with staggered delays
                    switch(id) {
                        case 'work_div1':
                            entry.target.style.animation = 'slideUpFadeLeft_d1 2s ease-out forwards';
                            break;
                        case 'work_div2':
                            entry.target.style.animation = 'slideUpFadeLeft_d2 2s ease-out 1s forwards';
                            break;
                        case 'work_div3':
                            entry.target.style.animation = 'slideUpFadeLeft_d3 2s ease-out 2s forwards';
                            break;
                        case 'work_div4':
                            entry.target.style.animation = 'slideUpFadeLeft_d4 2s ease-out 3s forwards';
                            break;
                    }
                }
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    function setupAnimations() {
        workDivs.forEach(div => {
            // Reset animations and classes
            div.style.animation = 'none';
            div.classList.remove('revealed');
            void div.offsetWidth; // Trigger reflow
            
            // Re-observe the element
            observer.observe(div);
        });
    }

    // Initial setup
    setupAnimations();
    
    // Handle resize - reset animations when screen size changes
    window.addEventListener('resize', setupAnimations);
});





// function home_child_mobile()
// {
//     let box1 = document.querySelector('#home_first_child1');
//     if (window.innerWidth <= 768) 
//     {
//         box1.classList.add('reveal-on-scroll');
//     } 
//     else 
//     {
//         box1.classList.remove('reveal-on-scroll', 'animated');
//         box1.style.animation = 'slideUpFadeIn_c1 2s ease-out forwards';
//         box1.style.overflow = 'hidden';
//     }
// }
// window.addEventListener('resize',home_child_mobile);
// document.addEventListener('DOMContentLoaded',home_child_mobile);


document.addEventListener('DOMContentLoaded', function() {
    let box1 = document.querySelector('#home_first_child1');
    let box2 = document.querySelector('#home_first_child2');
    let box3 = document.querySelector('#home_first_child3');
    let box4 = document.querySelector('#home_first_child4');
    
    // Initialize IntersectionObserver for mobile
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    function setupAnimations() {
        if (window.innerWidth <= 768) {
            // Mobile setup
            box1.classList.remove('revealed');
            box2.classList.remove('revealed');
            box3.classList.remove('revealed');
            box4.classList.remove('revealed');
            observer.observe(box1);
            observer.observe(box2);
            observer.observe(box3);
            observer.observe(box4);
        } else {
            // Desktop setup - ensure animation runs
            box1.style.animation = 'none';
            void box1.offsetWidth; // Trigger reflow
            box1.style.animation = 'slideUpFadeIn_c1 2s ease-out forwards';
            box2.style.animation = 'none';
            void box2.offsetWidth; 
            box2.style.animation = 'slideUpFadeIn_c2 2s ease-out forwards';
            box3.style.animation = 'none';
            void box3.offsetWidth; 
            box3.style.animation = 'slideUpFadeIn_c3 2s ease-out forwards';
            box4.style.animation = 'none';
            void box4.offsetWidth; 
            box4.style.animation = 'slideUpFadeIn_c4 2s ease-out forwards';
        }
    }

    // Initial setup
    setupAnimations();
    
    // Handle resize
    window.addEventListener('resize', setupAnimations);
});
