function initSliderPlatnosci() {
    gsap.registerPlugin(ScrollTrigger, Draggable, ScrollToPlugin);

    let iteration = 0;

    gsap.set('.card', {xPercent: 400, opacity: 0, scale: 0});

    const spacing = 0.1,
        snapTime = gsap.utils.snap(spacing),
        cards = gsap.utils.toArray('.card'),
        animateFunc = element => {
            const tl = gsap.timeline();
            tl.fromTo(element, {scale: 0, opacity: 0}, {scale: 1, opacity: 1, zIndex: 100, duration: 0.5, yoyo: true, repeat: 1, ease: "power1.in", immediateRender: false})
                .fromTo(element, {xPercent: 400}, {xPercent: -400, duration: 1, ease: "none", immediateRender: false}, 0);
            return tl;
        },
        seamlessLoop = buildSeamlessLoop(cards, spacing, animateFunc),
        playhead = {offset: 0},
        wrapTime = gsap.utils.wrap(0, seamlessLoop.duration()),
        scrub = gsap.to(playhead, {
            offset: 0,
            onUpdate() {
                seamlessLoop.time(wrapTime(playhead.offset));
            },
            duration: 0.5,
            ease: "power3",
            paused: true
        }),
        trigger = ScrollTrigger.create({
            trigger: ".platnosci-container",
            start: "top top",
            end: "+=3000",
            pin: true,
            onUpdate(self) {
                let scroll = self.scroll();
                if (scroll > self.end - 1) {
                    wrap(1, 2);
                } else if (scroll < 1 && self.direction < 0) {
                    wrap(-1, self.end - 2);
                } else {
                    scrub.vars.offset = (iteration + self.progress) * seamlessLoop.duration();
                    scrub.invalidate().restart();
                }
            }
        }),
        progressToScroll = progress => gsap.utils.clamp(1, trigger.end - 1, gsap.utils.wrap(0, 1, progress) * trigger.end),
        wrap = (iterationDelta, scrollTo) => {
            iteration += iterationDelta;
            // gsap.to(window, {scrollTo: {y: scrollTo}, duration: 0.5});
            // trigger.scroll(scrollTo);
            trigger.update();
        };

    function scrollToOffset(offset) {
        let snappedTime = snapTime(offset),
            progress = (snappedTime - seamlessLoop.duration() * iteration) / seamlessLoop.duration(),
            scroll = progressToScroll(progress);
            if (progress >= 1 || progress < 0) {
                wrap(Math.floor(progress), scroll);
            } else {
                gsap.to(window, {scrollTo: {y: scroll}, duration: 0.5});
            }
        }

    document.querySelector(".next").addEventListener("click", () => scrollToOffset(scrub.vars.offset + spacing));
    document.querySelector(".prev").addEventListener("click", () => scrollToOffset(scrub.vars.offset - spacing));

    function buildSeamlessLoop(items, spacing, animateFunc) {
        let rawSequence = gsap.timeline({paused: true}),
            seamlessLoop = gsap.timeline({
                paused: true,
                repeat: -1,
                onRepeat() {
                    this._time === this._dur && (this._tTime += this._dur - 0.01);
                },
                onReverseComplete() {
                    this.totalTime(this.rawTime() + this.duration() * 100);
                }
            }),
            cycleDuration = spacing * items.length,
            dur;

        items.concat(items).concat(items).forEach((item, i) => {
            let anim = animateFunc(items[i % items.length]);
            rawSequence.add(anim, i * spacing);
            dur || (dur = anim.duration());
        });

        seamlessLoop.fromTo(rawSequence, {
            time: cycleDuration + dur / 2
        }, {
            time: "+=" + cycleDuration,
            duration: cycleDuration,
            ease: "none"
        });
        return seamlessLoop;
    }

    Draggable.create(".drag-proxy", {
        type: "x",
        trigger: ".cards",
        onPress() {
            this.startOffset = scrub.vars.offset;
        },
        onDrag() {
            scrub.vars.offset = this.startOffset + (this.startX - this.x) * 0.001;
            scrub.invalidate().restart();
        },
        onDragEnd() {
            scrollToOffset(scrub.vars.offset);
        }
    });
        // Initialize Lenis for smooth scrolling
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
            infinite: false,
        });
    
        lenis.on('scroll', ({ scroll }) => {
            ScrollTrigger.update();
        });
    
        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
    
        requestAnimationFrame(raf);
    
}




document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".platnosci-container")) {
        initSliderPlatnosci();
    }
});