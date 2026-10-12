// Confirm before destructive admin actions
document.addEventListener('click', e => {
  const m = e.target.dataset && e.target.dataset.confirm;
  if (m && !confirm(m)) e.preventDefault();
});

// Bible quiz
const box = document.getElementById('quiz');
if (box) {
  const qs = JSON.parse(box.dataset.q).sort(() => Math.random() - 0.5);
  let i = 0, score = 0;
  const show = () => {
    if (i >= qs.length) {
      box.innerHTML = `<div class="card"><h3>You scored ${score} out of ${qs.length}</h3>
        <button class="btn" onclick="location.reload()">Play again</button></div>`;
      return;
    }
    const q = qs[i];
    box.innerHTML = `<div class="card"><p class="muted">Question ${i + 1} of ${qs.length}</p><h3></h3><div class="opts"></div></div>`;
    box.querySelector('h3').textContent = q.question;
    const opts = box.querySelector('.opts');
    ['a', 'b', 'c', 'd'].filter(k => q[k]).forEach(k => {
      const b = document.createElement('button');
      b.textContent = q[k];
      b.onclick = () => {
        const right = k === q.answer;
        if (right) score++;
        opts.querySelectorAll('button').forEach(x => x.disabled = true);
        b.classList.add(right ? 'right' : 'wrong');
        if (!right) [...opts.children].find(x => x.textContent === q[q.answer]).classList.add('right');
        setTimeout(() => { i++; show(); }, 1200);
      };
      opts.appendChild(b);
    });
  };
  show();
}
