<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern To-Do App</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h2 {
            text-align: center;
            color: #2d3748;
            font-size: 2.5rem;
            margin-bottom: 40px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        h3 {
            text-align: center;
            color: #4a5568;
            font-size: 1.5rem;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
            background: #f8fafc;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        button {
            width: 100%;
            padding: 16px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            font-family: inherit;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(-1px);
        }

        .logout-btn {
            width: auto;
            padding: 8px 16px;
            font-size: 0.9rem;
            margin: 0 0 0 10px;
            background: linear-gradient(135deg, #f56565, #fc8181);
        }

        .logout-btn:hover {
            box-shadow: 0 10px 20px rgba(245, 101, 101, 0.4);
        }

        .user-welcome {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            font-size: 1.2rem;
            color: #4a5568;
        }

        /* Task container untuk layout yang lebih baik */
        .task-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            animation: slideIn 0.4s ease-out;
        }

        /* Task utama tanpa delete button */
        .task {
            display: flex;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            flex: 1;
        }

        .task::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .task:hover::before {
            opacity: 1;
        }

        .task:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .task.completed {
            background: linear-gradient(135deg, #48bb78, #68d391);
            color: white;
            transform: scale(0.98);
        }

        .task.completed::before {
            background: rgba(255, 255, 255, 0.1);
            opacity: 1;
        }

        .task.completed:hover {
            transform: scale(0.98) translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
        }

        .task-checkbox {
            width: 24px;
            height: 24px;
            border: 3px solid #cbd5e0;
            border-radius: 50%;
            margin-right: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            flex-shrink: 0;
            background: white;
        }

        .task-checkbox::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            color: white;
            font-weight: bold;
            font-size: 14px;
            transition: transform 0.2s ease;
        }

        .task.completed .task-checkbox {
            background: white;
            border-color: white;
        }

        .task.completed .task-checkbox::after {
            transform: translate(-50%, -50%) scale(1);
            color: #48bb78;
        }

        .task-text {
            flex: 1;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .task.completed .task-text {
            text-decoration: line-through;
            opacity: 0.9;
        }

        /* Delete button terpisah */
        .task-delete {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #f56565, #fc8181);
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.2);
        }

        .task-delete:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.4);
        }

        .task-delete:active {
            transform: translateY(0) scale(1);
        }

        .add-task-container {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .add-task-input {
            flex: 1;
            margin: 0;
        }

        .add-task-btn {
            width: auto;
            padding: 16px 30px;
            margin: 0;
            white-space: nowrap;
        }

        .tasks-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .tasks-container::-webkit-scrollbar {
            width: 6px;
        }

        .tasks-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .tasks-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        .empty-state {
            text-align: center;
            color: #a0aec0;
            font-size: 1.1rem;
            margin: 40px 0;
            opacity: 0.8;
        }

        /* Auth Tabs Styling */
        .auth-tabs {
            display: flex;
            margin-bottom: 30px;
            background: #f8fafc;
            border-radius: 16px;
            padding: 6px;
            gap: 6px;
        }

        .auth-tab {
            flex: 1;
            padding: 12px 20px;
            background: transparent;
            color: #64748b;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0;
            font-family: inherit;
        }

        .auth-tab.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .auth-tab:hover:not(.active) {
            background: #e2e8f0;
            color: #475569;
        }

        /* Auth Forms */
        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
            animation: fadeInSlide 0.4s ease-out;
        }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .task-checkbox:hover {
            animation: bounce 0.6s ease;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 2rem;
            }

            .add-task-container {
                flex-direction: column;
            }

            .add-task-btn {
                width: 100%;
            }

            .task-item {
                gap: 8px;
            }

            .task-delete {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>✨ To-Do App</h2>

        <div id="auth-section">
            <!-- Auth Tabs -->
            <div class="auth-tabs">
                <button class="auth-tab active" onclick="switchAuthTab('login')">Sign In</button>
                <button class="auth-tab" onclick="switchAuthTab('register')">Create Account</button>
            </div>

            <!-- Login Form -->
            <div id="login-form" class="auth-form active">
                <h3>Welcome Back!</h3>
                <div class="form-group">
                    <input type="text" id="login-username" placeholder="Enter your username">
                </div>
                <div class="form-group">
                    <input type="password" id="login-password" placeholder="Enter your password">
                </div>
                <button onclick="login()">Sign In</button>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="auth-form">
                <h3>Join Us Today!</h3>
                <div class="form-group">
                    <input type="text" id="reg-username" placeholder="Choose a username">
                </div>
                <div class="form-group">
                    <input type="password" id="reg-password" placeholder="Create a password">
                </div>
                <button onclick="register()">Create Account</button>
            </div>
        </div>

        <div id="app-section" class="hidden">
            <div class="user-welcome">
                <span>Welcome back, <strong id="user-name"></strong>!</span>
                <button class="logout-btn" onclick="logout()">Logout</button>
            </div>

            <div class="add-task-container">
                <input type="text" id="new-task" class="add-task-input" placeholder="What needs to be done?"
                    onkeypress="handleEnterKey(event)">
                <button class="add-task-btn" onclick="addTask()">Add Task</button>
            </div>

            <div class="tasks-container">
                <div id="tasks-list"></div>
                <div id="empty-state" class="empty-state hidden">
                    🎉 No tasks yet! Add one above to get started.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auth tab switching function
        function switchAuthTab(tab) {
            // Update tab buttons
            const tabs = document.querySelectorAll('.auth-tab');
            tabs.forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');

            // Update forms
            const forms = document.querySelectorAll('.auth-form');
            forms.forEach(f => f.classList.remove('active'));

            if (tab === 'login') {
                document.getElementById('login-form').classList.add('active');
            } else {
                document.getElementById('register-form').classList.add('active');
            }
        }

        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function handleEnterKey(event) {
            if (event.key === 'Enter') {
                addTask();
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        async function register() {
            const username = document.getElementById('reg-username').value.trim();
            const password = document.getElementById('reg-password').value;

            if (!username || !password) {
                alert('Please fill in all fields');
                return;
            }

            try {
                const res = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        username,
                        password
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    showApp(username);
                    fetchTasks();
                } else {
                    console.error("Register error:", data);
                    alert(data.message || "Registration failed. Please try again.");
                }
            } catch (error) {
                console.error('Registration error:', error);
                alert('Registration failed. Please check your connection.');
            }
        }

        async function login() {
            const username = document.getElementById('login-username').value.trim();
            const password = document.getElementById('login-password').value;

            if (!username || !password) {
                alert('Please fill in all fields');
                return;
            }

            try {
                const res = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        username,
                        password
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    showApp(username);
                    fetchTasks();
                } else {
                    console.error("Login error:", data);
                    alert(data.message || "Login failed. Please check your credentials.");
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Login failed. Please check your connection.');
            }
        }

        async function logout() {
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                document.getElementById('auth-section').classList.remove('hidden');
                document.getElementById('app-section').classList.add('hidden');
                document.getElementById('tasks-list').innerHTML = '';

                // Clear form fields
                document.getElementById('reg-username').value = '';
                document.getElementById('reg-password').value = '';
                document.getElementById('login-username').value = '';
                document.getElementById('login-password').value = '';

                // Reset to login tab
                switchAuthTab('login');
                document.querySelector('.auth-tab').click();
            } catch (error) {
                console.error('Logout error:', error);
            }
        }

        // Global function untuk delete (supaya bisa dipanggil dari onclick)
        window.deleteTaskGlobal = function(id) {
            console.log('🗑️ Global delete function called with ID:', id);
            deleteTask(id);
        };

        async function fetchTasks() {
            try {
                const res = await fetch('/tasks', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const tasks = await res.json();
                const list = document.getElementById('tasks-list');
                const emptyState = document.getElementById('empty-state');

                list.innerHTML = '';

                if (tasks.length === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');

                    tasks.forEach((task, i) => {
                        // Container untuk task item dan delete button
                        const taskItem = document.createElement('div');
                        taskItem.className = 'task-item';
                        taskItem.style.animationDelay = `${i * 100}ms`;

                        // Task utama
                        const taskDiv = document.createElement('div');
                        taskDiv.className = `task ${task.completed ? 'completed' : ''}`;
                        taskDiv.innerHTML = `
                            <div class="task-checkbox" onclick="toggleTask(${task.id})"></div>
                            <div class="task-text" onclick="toggleTask(${task.id})">${task.title}</div>
                        `;

                        // Delete button terpisah
                        const deleteBtn = document.createElement('button');
                        deleteBtn.className = 'task-delete';
                        deleteBtn.innerHTML = '✕';
                        deleteBtn.onclick = function() {
                            console.log('Button clicked!');
                            deleteTaskGlobal(task.id);
                            return false;
                        };

                        // Gabungkan ke container
                        taskItem.appendChild(taskDiv);
                        taskItem.appendChild(deleteBtn);
                        list.appendChild(taskItem);
                    });
                }
            } catch (error) {
                console.error('Fetch tasks error:', error);
            }
        }

        async function addTask() {
            const title = document.getElementById('new-task').value.trim();
            if (!title) return;

            try {
                const res = await fetch('/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title
                    })
                });

                if (res.ok) {
                    document.getElementById('new-task').value = '';
                    fetchTasks();
                } else {
                    alert('Failed to add task. Please try again.');
                }
            } catch (error) {
                console.error('Add task error:', error);
                alert('Failed to add task. Please check your connection.');
            }
        }

        async function toggleTask(id) {
            try {
                await fetch(`/tasks/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                fetchTasks();
            } catch (error) {
                console.error('Toggle task error:', error);
            }
        }

        async function deleteTask(id) {
            console.log('🗑️ Attempting to delete task with ID:', id);

            try {
                console.log('📡 Sending DELETE request to:', `/tasks/${id}`);
                console.log('🔑 Using CSRF token:', csrfToken);

                const response = await fetch(`/tasks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('📋 Response status:', response.status);
                console.log('📋 Response ok:', response.ok);

                if (response.ok) {
                    console.log('✅ Task deleted successfully, refreshing list...');
                    await fetchTasks();
                } else {
                    const errorText = await response.text();
                    console.error('❌ Delete failed:', response.status, errorText);
                    alert(`Failed to delete task. Status: ${response.status}`);
                }

            } catch (error) {
                console.error('💥 Delete request failed with error:', error);
                alert('Network error: Could not delete task');
            }
        }

        function showApp(username) {
            document.getElementById('user-name').textContent = username;
            document.getElementById('auth-section').classList.add('hidden');
            document.getElementById('app-section').classList.remove('hidden');
        }
    </script>
</body>

</html>
