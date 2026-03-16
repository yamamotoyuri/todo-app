import { useState, useEffect } from 'react'
import './App.css'

function App() {
  const [todos, setTodos] = useState([])
  const [newTitle, setNewTitle] = useState("")

  const API_BASE_URL = 'http://localhost:8080/api';

  const fetchTodos = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/todos`, {
        headers: { 'Accept': 'application/json' }
      })
      if (!response.ok) throw new Error('fetch error')
      const data = await response.json()
      setTodos(data)
    } catch (e) {
      console.error('データ取得失敗', e)
    }
  }

  useEffect(() => {
    fetchTodos()
  }, [])

  const addTodo = async () => {
    if (!newTitle) return
    try {
      const response = await fetch(`${API_BASE_URL}/todos`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json' 
        },
        body: JSON.stringify({ title: newTitle }),
      })
      if (!response.ok) throw new Error('add error')
      setNewTitle("")
      fetchTodos()
    } catch (e) {
      console.error('追加失敗', e)
    }
  }

  const deleteTodo = async (Todo) => {
    try {
      const response = await fetch(`${API_BASE_URL}/todos/${Todo.id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
      })
      if (!response.ok) throw new Error('delete error')
      fetchTodos()
    } catch (e) {
      console.error('削除失敗', e)
    }
  }

  const toggleComplete = async (Todo) => {
    try {
      const response = await fetch(`${API_BASE_URL}/todos/${Todo.id}`, {
        method: 'PUT',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json' 
        },
        body: JSON.stringify({ is_completed: !Todo.is_completed }),
      })
      if (!response.ok) throw new Error('update error')
      fetchTodos()
    } catch (e) {
      console.error('更新失敗', e)
    }
  }

  // エクスポート機能
  const downloadTodo = async (type) => {
    try {
      window.location.href = `${API_BASE_URL}/todo/export?type=${type}`;
    } catch (e) {
      console.error('ダウンロード失敗', e)
    }
  }
  

  return (
    <div style={{ padding: '20px', maxWidth: '500px', margin: 'auto', fontFamily: 'sans-serif' }}>
      <h1>My Todo</h1>

      <div style={{ marginBottom: '20px', display: 'flex', gap: '10px' }}>
        <input
          value={newTitle}
          onChange={(e) => setNewTitle(e.target.value)}
          placeholder="タスクを入力"
          style={{ flex: 1, padding: '8px' }}
        />
        <button onClick={addTodo}>Add</button>
      </div>

      <div style={{ marginBottom: '20px', display: 'flex', gap: '10px' }}>
        <button onClick={() => downloadTodo('excel')}>Excel出力</button>
        <button onClick={() => downloadTodo('pdf')}>PDF出力</button>
      </div>

      <ul style={{ listStyle: 'none', padding: 0 }}>
        {todos.map(Todo => (
          <li key={Todo.id} style={{ display: 'flex', alignItems: 'center', padding: '10px', borderBottom: '1px solid #eee' }}>
            <input
              type="checkbox"
              checked={!!Todo.is_completed} // booleanに変換
              onChange={() => toggleComplete(Todo)}
              style={{ marginRight: '10px' }}
            />
            <span style={{ flex: 1, textDecoration: Todo.is_completed ? 'line-through' : 'none', color: Todo.is_completed ? '#888' : '#000' }}>
              {Todo.title}
            </span>
            <button
              onClick={() => deleteTodo(Todo)}
              disabled={!Todo.is_completed}
              style={{
                backgroundColor: Todo.is_completed ? '#ff4d4f' : '#ccc',
                color: 'white', border: 'none', borderRadius: '4px', padding: '5px 10px',
                cursor: Todo.is_completed ? 'pointer' : 'not-allowed'
              }}
            >
              Delete
            </button>
          </li>
        ))}
      </ul>
    </div>
  )
}

export default App