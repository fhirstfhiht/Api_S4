let token = "";

function connexion() {
  const login = document.getElementById("login").value;
  const password = document.getElementById("password").value;

  fetch("/auth_api/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ login, password })
  })
    .then(res => res.json())
    .then(data => {
      if (data.token) {
        token = data.token;
        document.getElementById("token-status").innerText = "✅ Connecté !";
      } else {
        document.getElementById("token-status").innerText = "❌ Échec de connexion";
      }
    });
}

// ---------------------------- JOUEURS ----------------------------
function chargerJoueurs() {
  fetch("/back_end/joueurs.php", {
    headers: { "Authorization": `Bearer ${token}` }
  })
    .then(res => res.json())
    .then(joueurs => {
      const ul = document.getElementById("liste-joueurs");
      ul.innerHTML = "";
      joueurs.forEach(j => {
        const li = document.createElement("li");
        li.textContent = `${j.Nom} ${j.Prenom} - ${j.Poste_Joueur} (${j.Numero_Licence})`;
        ul.appendChild(li);
      });
    });
}

function ajouterJoueur() {
  const joueur = {
    Numero_Licence: document.getElementById("ajout-licence").value,
    Nom: document.getElementById("ajout-nom").value,
    Prenom: document.getElementById("ajout-prenom").value,
    Date_De_Naissance: document.getElementById("ajout-naissance").value,
    Taille: document.getElementById("ajout-taille").value,
    Poids: document.getElementById("ajout-poids").value,
    Commentaires: null,
    Poste_Joueur: document.getElementById("ajout-poste").value,
    Id_Statut: document.getElementById("ajout-statut").value
  };

  fetch("/back_end/joueurs.php", {
    method: "POST",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify(joueur)
  })
    .then(res => res.json())
    .then(() => {
      alert("✅ Joueur ajouté !");
      chargerJoueurs();
    });
}

function modifierJoueur() {
  const licence = document.getElementById("modif-licence").value;
  const joueur = {
    Nom: document.getElementById("modif-nom").value,
    Prenom: document.getElementById("modif-prenom").value,
    Date_De_Naissance: document.getElementById("modif-naissance").value,
    Taille: document.getElementById("modif-taille").value,
    Poids: document.getElementById("modif-poids").value,
    Commentaires: null,
    Poste_Joueur: document.getElementById("modif-poste").value,
    Id_Statut: document.getElementById("modif-statut").value
  };

  fetch(`/back_end/joueurs.php?Numero_Licence=${licence}`, {
    method: "PUT",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify(joueur)
  })
    .then(res => res.json())
    .then(() => {
      alert("✏️ Joueur modifié !");
      chargerJoueurs();
    });
}

function supprimerJoueur() {
  const licence = document.getElementById("suppr-licence").value;

  fetch(`/back_end/joueurs.php?Numero_Licence=${licence}`, {
    method: "DELETE",
    headers: {
      "Authorization": `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(() => {
      alert("❌ Joueur supprimé !");
      chargerJoueurs();
    });
}

// ---------------------------- MATCHS ----------------------------
function chargerMatchs() {
  fetch("/back_end/matchs.php", {
    headers: { "Authorization": `Bearer ${token}` }
  })
    .then(res => res.json())
    .then(matchs => {
      const ul = document.getElementById("liste-matchs");
      ul.innerHTML = "";
      matchs.forEach(m => {
        const li = document.createElement("li");
        li.textContent = `${m.Id_Match} - ${m.Adversaire} (${m.Date_Heure_Match})`; // adapte si besoin
        ul.appendChild(li);
      });
    });
}

function ajouterMatch() {
  const match = {
    Id_Match: document.getElementById("ajout-match-id").value,
    Date_Heure_Match: document.getElementById("ajout-dateheure").value,
    Lieu: document.getElementById("ajout-lieu").value,
    Adversaire: document.getElementById("ajout-adversaire").value,
    Score_Equipe: document.getElementById("ajout-score-equipe").value,
    Score_Adversaire: document.getElementById("ajout-score-adv").value,
    Victoire: document.getElementById("ajout-victoire").value,
    Egalite: document.getElementById("ajout-egalite").value
  };

  fetch("/back_end/matchs.php", {
    method: "POST",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify(match)
  })
    .then(res => res.json())
    .then(() => alert("✅ Match ajouté !"));
}

function modifierMatch() {
  const id = document.getElementById("modif-match-id").value;
  const match = {
    Date_Heure_Match: document.getElementById("modif-dateheure").value,
    Lieu: document.getElementById("modif-lieu").value,
    Adversaire: document.getElementById("modif-adversaire").value,
    Score_Equipe: document.getElementById("modif-score-equipe").value,
    Score_Adversaire: document.getElementById("modif-score-adv").value,
    Victoire: document.getElementById("modif-victoire").value,
    Egalite: document.getElementById("modif-egalite").value
  };

  fetch(`/back_end/matchs.php?Id_Match=${id}`, {
    method: "PUT",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify(match)
  })
    .then(res => res.json())
    .then(() => alert("✏️ Match modifié !"));
}

function chargerFeuilleMatch() {
  const idMatch = document.getElementById("feuille-id-match").value;

  fetch(`/back_end/feuilles.php?Id_Match=${idMatch}`, {
    headers: {
      "Authorization": `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(participants => {
      const ul = document.getElementById("liste-feuille");
      ul.innerHTML = "";
      participants.forEach(p => {
        const li = document.createElement("li");
        li.textContent = `${p.Numero_Licence} - ${p.Statut_Participation} - ${p.Poste_Match} - Note: ${p.Note}`;
        ul.appendChild(li);
      });
    });
}

function supprimerMatch() {
  const id = document.getElementById("suppr-match-id").value;

  fetch(`/back_end/matchs.php?Id_Match=${id}`, {
    method: "DELETE",
    headers: {
      "Authorization": `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(() => alert("❌ Match supprimé !"));
}

// ---------------------------- PARTICIPATIONS ----------------------------
function ajouterParticipation() {
  const data = {
    Numero_Licence: document.getElementById("part-licence").value,
    Id_Match: document.getElementById("part-id-match").value,
    Statut_Participation: document.getElementById("part-statut").value,
    Poste_Match: document.getElementById("part-poste").value,
    Note: document.getElementById("part-note").value
  };

  fetch("/back_end/feuilles.php", {
    method: "POST",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(() => alert("✅ Participation ajoutée !"));
}

function modifierParticipation() {
  const numero = document.getElementById("modif-licence-part").value;
  const match = document.getElementById("modif-id-match").value;
  const data = {
    Statut_Participation: document.getElementById("modif-statut-part").value,
    Poste_Match: document.getElementById("modif-poste-part").value,
    Note: document.getElementById("modif-note-part").value
  };

  fetch(`/back_end/feuilles.php?Numero_Licence=${numero}&Id_Match=${match}`, {
    method: "PUT",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(() => alert("✏️ Participation modifiée !"));
}

function supprimerParticipation() {
  const numero = document.getElementById("suppr-licence-part").value;
  const match = document.getElementById("suppr-id-match-part").value;

  fetch(`/back_end/feuilles.php?Numero_Licence=${numero}&Id_Match=${match}`, {
    method: "DELETE",
    headers: {
      "Authorization": `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(() => alert("❌ Participation supprimée !"));
}

// ---------------------------- STATS ----------------------------
function chargerStats() {
  fetch("/back_end/stats.php", {
    headers: {
      "Authorization": `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(stats => {
      document.getElementById("stats").textContent = JSON.stringify(stats, null, 2);
    });
}
