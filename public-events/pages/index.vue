<template>
  <div class="container">
    <div class="card">
      <h1>Welcome to the Event Platform</h1>
      <p>Try visiting /org-name/events to see events.</p>
      <div class="buttons">
        <NuxtLink to="/admin/login" class="btn btn-primary">Login</NuxtLink>
        <NuxtLink to="/admin/register" class="btn btn-outline">Register</NuxtLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #a8dadc, #457b9d);
  padding: 20px;
}

.card {
  background: white;
  padding: 40px;
  max-width: 480px;
  width: 100%;
  text-align: center;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

h1 {
  font-size: 2.5rem;
  color: #1d3557;
  margin-bottom: 20px;
}

p {
  font-size: 1.1rem;
  color: #333;
  margin-bottom: 30px;
}

.buttons {
  display: flex;
  justify-content: center;
  gap: 16px;
}

.btn {
  text-decoration: none;
  padding: 12px 24px;
  font-weight: 600;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary {
  background-color: #1d3557;
  color: white;
  border: none;
}

.btn-primary:hover {
  background-color: #457b9d;
}

.btn-outline {
  border: 2px solid #1d3557;
  color: #1d3557;
  background-color: transparent;
}

.btn-outline:hover {
  background-color: #1d3557;
  color: white;
}
</style>
