import './App.css';
import {useState} from 'react';

export default function App(){
  const [alunni, setAlunni] = useState([]);
  const[loading, setLoading] = useState(false);
  const[isAdding, setIsAdding] = useState(false);
  const [newNome, setNewNome] = useState("");
  const [newCognome, setNewCognome] = useState("");

  function carica(){
    setLoading(true);
    fetch('http://localhost:8080/alunni')
    .then(response => response.json())
    .then(function(data){
      setTimeout(() => {
      setAlunni(data);
      setLoading(false);
      }, 2000);
    })
  }

  function aggiungiAlunno() {
    setIsAdding(true);
  }

  function salvaAlunno() {
    const nuovoAlunno = { nome: newNome, cognome: newCognome };

    fetch('http://localhost:8080/alunni', {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(nuovoAlunno)
    })
    .then(response => {
    return response.json();
    })

    .then(data => {
      carica();
    setIsAdding(false);
    setNewNome("");
    setNewCognome("");
    })
    .catch(error => {
      console.error("Errore nel salvataggio:", error);
      alert("Errore nel salvataggio, riprova.");
    });
  }

  function eliminaAlunno(id) {
    const conferma = window.confirm("Sei sicuro di voler eliminare questo alunno?");
    
    if (!conferma) return; // Se l'utente preme "Annulla", non facciamo nulla
  
    fetch(`http://localhost:8080/alunni/${id}`, {
      method: "DELETE",
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`Errore HTTP ${response.status}`);
      }
      return response.json();
    })
    .then(() => {
      setAlunni(alunni.filter(alunno => alunno.id !== id));
    })
  }
  

  function annullaAggiunta() {
    setIsAdding(false);
    setNewNome("");
    setNewCognome("");
  }

  return(
    <>
      <div>
        {loading && (
          <div className="spinner-container">
            <div className="spinner"></div>
            <span className="loading-text">Caricamento in corso...</span>
          </div>
        )}

        {!loading && alunni.length === 0 && (
          <button onClick={carica}>Carica alunni</button>
        )}

        {alunni.length > 0 && (
          <>
            <table>
              {alunni.map(alunno => (
                <tr>
                  <td>{alunno.id}</td>
                  <td>{alunno.nome}</td>
                  <td>{alunno.cognome}</td>
                  <td>
                    <button onClick={() => eliminaAlunno(alunno.id)}>Delete</button>
                  </td>
                </tr>
              ))}
            </table>

            {!isAdding && (
              <button onClick={aggiungiAlunno}>Aggiungi alunno</button>
            )}

            {isAdding && (
              <div className="form-container">
                <input
                  type="text"
                  placeholder="Nome"
                  value={newNome}
                  onChange={(e) => setNewNome(e.target.value)}  //e.target.value prende il valore che l'utente sta digitando e lo memorizza in newNome
                />
                <input
                  type="text"
                  placeholder="Cognome"
                  value={newCognome}
                  onChange={(e) => setNewCognome(e.target.value)}
                />
                <button onClick={salvaAlunno}>Salva</button>
                <button onClick={annullaAggiunta}>Annulla</button>
              </div>
            )}
          </>
        )}
      </div>
    </>
  );
}