<script>
// Slider functionality
let currentSlide = 0
const totalSlides = 3

function goToSlide(slideIndex) {
  currentSlide = slideIndex
  const sliderWrapper = document.getElementById("sliderWrapper")
  if (!sliderWrapper) return
  const translateX = -slideIndex * 100
  sliderWrapper.style.transform = `translateX(${translateX}%)`

  // Update dots
  const dots = document.querySelectorAll(".slider-dot")
  dots.forEach((dot, index) => {
    if (index === slideIndex) {
      dot.classList.remove("bg-gray-300")
      dot.classList.add("bg-black")
    } else {
      dot.classList.remove("bg-black")
      dot.classList.add("bg-gray-300")
    }
  })
}

// Auto slide functionality
function nextSlide() {
  currentSlide = (currentSlide + 1) % totalSlides
  goToSlide(currentSlide)
}

// Auto slide every 5 seconds
setInterval(nextSlide, 5000)

// Mobile menu toggle
function toggleMobileMenu() {
  const mobileMenu = document.getElementById("mobileMenu")
  if (mobileMenu) {
    mobileMenu.classList.toggle("hidden")
  }
}

// FAQ toggle functionality
function toggleFAQ(index) {
  const faqItems = document.querySelectorAll(".faq-item")
  const currentItem = faqItems[index]
  if (!currentItem) return
  const answer = currentItem.querySelector(".faq-answer")
  const icon = currentItem.querySelector(".faq-icon")

  // Toggle current FAQ
  answer?.classList.toggle("hidden")
  icon?.classList.toggle("rotate-180")

  // Close other FAQs
  faqItems.forEach((item, i) => {
    if (i !== index) {
      const otherAnswer = item.querySelector(".faq-answer")
      const otherIcon = item.querySelector(".faq-icon")
      otherAnswer?.classList.add("hidden")
      otherIcon?.classList.remove("rotate-180")
    }
  })
}

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const href = this.getAttribute("href")
    if (!href) return
    const isHashOnly = href.startsWith('#')
    if (isHashOnly) {
      e.preventDefault()
      const target = document.querySelector(href)
      target?.scrollIntoView({ behavior: "smooth", block: "start" })
      const mobileMenu = document.getElementById("mobileMenu")
      mobileMenu?.classList.add("hidden")
    }
  })
})

// Add scroll effect to header
window.addEventListener("scroll", () => {
  const header = document.querySelector("header")
  if (!header) return
  if (window.scrollY > 100) {
    header.classList.add("shadow-md")
  } else {
    header.classList.remove("shadow-md")
  }

  if (window.scrollY > 50) {
    header.classList.remove("bg-white/70", "border-black/10")
    header.classList.add("bg-white/80", "backdrop-blur-2xl", "backdrop-saturate-200", "border-black/20", "shadow-sm")
  } else {
    header.classList.remove("bg-white/80", "backdrop-blur-2xl", "backdrop-saturate-200", "border-black/20", "shadow-sm")
    header.classList.add("bg-white/70", "border-black/10")
  }
})

function initSuccessPageAnimations() {
  setTimeout(() => {
    const successTexts = document.querySelectorAll(".success-text")
    successTexts.forEach((text) => {
      text.style.animation = "fadeInUp 0.8s ease-out forwards"
    })
  }, 200)

  setTimeout(() => {
    const circle = document.querySelector(".success-circle")
    if (circle) {
      circle.style.animation = "scaleIn 0.6s ease-out forwards"
    }
  }, 600)

  setTimeout(() => {
    const bg = document.getElementById("successBg")
    if (bg) {
      bg.style.transform = "scale(1)"
    }
  }, 1000)

  setTimeout(() => {
    const button = document.querySelector(".success-button")
    if (button) {
      button.style.animation = "fadeInUp 0.8s ease-out forwards"
    }
  }, 1400)

  setTimeout(() => {
    const circle = document.querySelector(".success-circle > div")
    if (circle) {
      setInterval(() => {
        circle.style.transform = "translateY(-5px)"
        setTimeout(() => {
          circle.style.transform = "translateY(0px)"
        }, 1000)
      }, 2000)
    }
  }, 2000)
}

document.addEventListener("DOMContentLoaded", () => {
  if (document.querySelector(".success-circle")) {
    initSuccessPageAnimations()

    // Robust animation for SVG checkmark
    const path = document.getElementById('checkmark')
    if (path && typeof path.getTotalLength === 'function') {
      try {
        const len = Math.ceil(path.getTotalLength()) || 100
        path.style.strokeDasharray = String(len)
        path.style.strokeDashoffset = String(len)
        // start draw slightly after bg scale animation
        setTimeout(() => {
          path.style.transition = 'stroke-dashoffset 800ms ease-out'
          path.style.strokeDashoffset = '0'
        }, 1100)
      } catch (e) {
        // Fallback: show instantly
        path.style.strokeDasharray = 'none'
        path.style.strokeDashoffset = '0'
      }
    }
  }
})
</script>
