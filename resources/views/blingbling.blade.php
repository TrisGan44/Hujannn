<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Crystal Light Column</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  html,body{height:100%}
  body{
    background: #000;
    overflow:hidden;
    user-select:none;
  }
  #fx{
    position:fixed; inset:0; width:100%; height:100%;
    filter: saturate(1.4) contrast(1.3);
  }
</style>
</head>
<body>
<canvas id="fx"></canvas>
<script>
(() => {
  const cvs = document.getElementById('fx');
  const ctx = cvs.getContext('2d', { alpha:true });
  let DPR=1,W=0,H=0;
  const resize=()=>{DPR=Math.min(devicePixelRatio||1,2.5);W=innerWidth*DPR;H=innerHeight*DPR;cvs.width=W;cvs.height=H;cvs.style.width=innerWidth+'px';cvs.style.height=innerHeight+'px';};
  addEventListener('resize', resize, {passive:true}); resize();

  const rand=(a,b)=>a+Math.random()*(b-a);
  const now=()=>performance.now();

  // Crystal color palette
  const colors = {
    primary: '#00d2ff',      // Electric blue
    secondary: '#00ffff',    // Bright cyan
    tertiary: '#00b3b3',     // Teal
    accent: '#0080ff',       // Deep blue
    highlight: '#ffffff'    // White
  };

  // Create structured crystal blocks
  function createCrystalBlocks() {
    const blocks = [];
    const centerX = W / 2;
    const centerY = H / 2;
    const columnWidth = 140 * DPR;
    const columnHeight = 700 * DPR;
    
    // Create main structural blocks
    const numBlocks = 12;
    for(let i = 0; i < numBlocks; i++) {
      const y = centerY + (i - numBlocks/2) * (columnHeight / numBlocks);
      const height = rand(0.6, 1.2) * (columnHeight / numBlocks);
      
      // Create 2-4 blocks per row for layered effect
      const blocksPerRow = Math.floor(rand(2, 4));
      for(let j = 0; j < blocksPerRow; j++) {
        const x = centerX + (j - blocksPerRow/2) * (columnWidth / blocksPerRow) + rand(-20, 20) * DPR;
        const width = rand(0.7, 1.3) * (columnWidth / blocksPerRow);
        
        blocks.push({
          x, y, width, height,
          opacity: rand(0.4, 0.8),
          shimmer: rand(0, Math.PI * 2),
          shimmerSpeed: rand(0.01, 0.03),
          color: Math.random() < 0.4 ? colors.primary : 
                 Math.random() < 0.6 ? colors.secondary : 
                 Math.random() < 0.8 ? colors.tertiary : colors.accent,
          layer: j
        });
      }
    }
    
    // Add some smaller detail blocks
    for(let i = 0; i < 15; i++) {
      const x = centerX + rand(-0.8, 0.8) * columnWidth;
      const y = centerY + rand(-1.2, 1.2) * columnHeight * 0.4;
      const width = rand(0.3, 0.8) * columnWidth;
      const height = rand(0.2, 0.6) * (columnHeight / numBlocks);
      
      blocks.push({
        x, y, width, height,
        opacity: rand(0.3, 0.6),
        shimmer: rand(0, Math.PI * 2),
        shimmerSpeed: rand(0.02, 0.05),
        color: Math.random() < 0.5 ? colors.secondary : colors.tertiary,
        layer: 3
      });
    }
    
    return blocks;
  }

  // Create scattered particles
  function createParticles() {
    const particles = [];
    const centerX = W / 2;
    const centerY = H / 2;
    
    // Cyan and blue particles around the column
    for(let i = 0; i < 20; i++) {
      particles.push({
        x: centerX + rand(-200, 200) * DPR,
        y: centerY + rand(-300, 300) * DPR,
        size: rand(1, 3) * DPR,
        color: Math.random() < 0.7 ? '#00ffff' : '#00d2ff',
        opacity: rand(0.2, 0.5),
        shimmer: rand(0, Math.PI * 2),
        shimmerSpeed: rand(0.02, 0.06)
      });
    }
    
    return particles;
  }

  const crystalBlocks = createCrystalBlocks();
  const particles = createParticles();

  // Render loop
  let last = now();
  function loop() {
    const t = now();
    const dt = t - last;
    last = t;

    // Clear with fade
    ctx.globalCompositeOperation = 'source-over';
    ctx.fillStyle = 'rgba(0,0,0,0.08)';
    ctx.fillRect(0, 0, W, H);

    // Draw subtle background glow
    ctx.globalAlpha = 0.03;
    ctx.fillStyle = colors.primary;
    ctx.fillRect(W/2 - 100*DPR, H/2 - 350*DPR, 200*DPR, 700*DPR);

    // Draw crystal blocks (back to front)
    for(let layer = 3; layer >= 0; layer--) {
      crystalBlocks.filter(b => b.layer === layer).forEach(block => {
        const shimmer = Math.sin(t * 0.001 * block.shimmerSpeed + block.shimmer) * 0.2 + 0.8;
        ctx.globalAlpha = block.opacity * shimmer;
        
        ctx.save();
        ctx.translate(block.x, block.y);
        
        // Create subtle gradient for each block
        const gradient = ctx.createLinearGradient(-block.width * 0.5, 0, block.width * 0.5, 0);
        gradient.addColorStop(0, block.color);
        gradient.addColorStop(0.5, block.color);
        gradient.addColorStop(1, block.color);
        
        ctx.fillStyle = gradient;
        ctx.fillRect(-block.width * 0.5, -block.height * 0.5, block.width, block.height);
        
        ctx.restore();
      });
    }

    // Draw bright center square
    ctx.globalAlpha = 0.9;
    ctx.fillStyle = colors.highlight;
    ctx.fillRect(W/2 - 12*DPR, H/2 - 12*DPR, 24*DPR, 24*DPR);

    // Draw particles
    particles.forEach(particle => {
      const shimmer = Math.sin(t * 0.001 * particle.shimmerSpeed + particle.shimmer) * 0.3 + 0.7;
      ctx.globalAlpha = particle.opacity * shimmer;
      
      ctx.save();
      ctx.translate(particle.x, particle.y);
      ctx.fillStyle = particle.color;
      ctx.fillRect(-particle.size * 0.5, -particle.size * 0.5, particle.size, particle.size);
      ctx.restore();
    });

    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);
})();
</script>
</body>
</html>
