// =========== 1. TYPING EFFECT ===========
const typingText = document.getElementById('typing-text');
const names = ['Mahasiswa SI', 'Muhamad Sholichin', 'Web Developer'];
let nameIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeEffect() {
  const currentName = names[nameIndex];
  if (isDeleting) {
    typingText.textContent = currentName.substring(0, charIndex - 1);
    charIndex--;
  } else {
    typingText.textContent = currentName.substring(0, charIndex + 1);
    charIndex++;
  }

  let delay = isDeleting ? 50 : 100;
  if (!isDeleting && charIndex === currentName.length) {
    delay = 2000; // Jeda saat teks selesai diketik
    isDeleting = true;
  } else if (isDeleting && charIndex === 0) {
    isDeleting = false;
    nameIndex = (nameIndex + 1) % names.length;
    delay = 500; // Jeda sebelum mengetik kata baru
  }
  setTimeout(typeEffect, delay);
}
typeEffect(); // Mulai efek

// =========== 2. GENERATE PROJECT CARDS ===========
const projects = [
  { 
    title: 'Website Profil', 
    desc: 'Website profil dengan HTML & CSS', 
    image: 'images/profil.png', // Intentional missing image to match screenshot behavior
    url: '../profil/' 
  },
  { 
    title: 'Aplikasi Donasi', 
    desc: 'Sistem manajemen CRUD donatur dengan PHP dan MySQL', 
    image: 'images/donasi.png', 
    url: '../donasi sederhana/' 
  },
  { 
    title: 'Kalkulator Sederhana', 
    desc: 'Kalkulator interaktif', 
    image: 'images/kalkulator.png', 
    url: '../kalkulator/' 
  },
  { 
    title: 'Form Interaktif', 
    desc: 'Form pendaftaran dengan validasi', 
    image: 'images/form.png', 
    url: '#' 
  }
];

const projectGrid = document.getElementById('project-grid');
projects.forEach(project => {
  const card = document.createElement('div');
  card.className = 'project-card';
  card.innerHTML = `
    <a href="${project.url}" style="text-decoration: none; color: inherit; display: block;">
      <img src="${project.image}" alt="${project.title}">
      <h3 style="color: #2563eb; text-decoration: underline;">${project.title}</h3>
      <p>${project.desc}</p>
    </a>
  `;
  projectGrid.appendChild(card);
});
