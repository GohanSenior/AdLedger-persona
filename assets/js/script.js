// Password visibility toggle
document.querySelectorAll(".toggle-password-btn").forEach((toggleBtn) => {
  toggleBtn.addEventListener("click", () => {
    const passwordInput = toggleBtn
      .closest(".password-input-wrapper")
      .querySelector("input");
    if (passwordInput) {
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";
      toggleBtn.classList.toggle("active");
    }
  });
});

// Validation des champs email, mot de passe, téléphone et code postal avec data-validate
const validationRules = {
  email: {
    pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  },
  password: {
    minLength: 8,
  },
  username: {
    minLength: 5,
  },
  phone: {
    pattern: /^(\d{2}[\s.]?){4}\d{2}$/,
  },
  zipcode: {
    pattern: /^\d{5}$/,
  },
};

document.querySelectorAll("input[data-validate]").forEach((input) => {
  const validateType = input.getAttribute("data-validate");
  const rules = validationRules[validateType];
  if (rules) {
    input.addEventListener("input", () => {
      const value = input.value.trim();
      if (value === "") {
        input.setCustomValidity(""); // laisse le 'required' gérer le vide
        input.classList.remove("is-valid", "is-invalid"); // état neutre
      } else {
        if (rules.pattern && !rules.pattern.test(value)) {
          input.classList.add("is-invalid");
          input.classList.remove("is-valid");
        } else if (rules.minLength && value.length < rules.minLength) {
          input.classList.add("is-invalid");
          input.classList.remove("is-valid");
        } else {
          input.classList.remove("is-invalid");
          input.classList.add("is-valid");
        }
      }
    });
  }
});

// Validation du mot de passe de confirmation
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password");

if (passwordInput && confirmInput) {
  confirmInput.addEventListener("input", function () {
    if (confirmInput.value === "") {
      confirmInput.setCustomValidity("");
      confirmInput.classList.remove("is-valid", "is-invalid");
    } else if (confirmInput.value === passwordInput.value) {
      confirmInput.setCustomValidity("");
      confirmInput.classList.add("is-valid");
      confirmInput.classList.remove("is-invalid");
    } else {
      confirmInput.setCustomValidity("Les mots de passe ne correspondent pas.");
      confirmInput.classList.add("is-invalid");
      confirmInput.classList.remove("is-valid");
    }
  });
}

// Bloquer les caractères invalides dans les champs de type number (e, E, +, -, . et ,)
document.querySelectorAll("input[type='number']").forEach((input) => {
  input.addEventListener("keydown", (e) => {
    if (["e", "E", "+", "-", ".", ","].includes(e.key)) {
      e.preventDefault();
    }
  });
});

// Gestion de la navigation entre les étapes du formulaire
if (document.querySelectorAll(".step").length > 0) {
  let current = 0; // Index de l'étape courante
  const steps = document.querySelectorAll(".step"); // Toutes les étapes du formulaire
  const prevBtn = document.getElementById("prevBtn"); // Bouton Précédent
  const nextBtn = document.getElementById("nextBtn"); // Bouton Suivant
  const submitBtn = document.getElementById("submitBtn"); // Bouton Soumettre

  function showStep(i) {
    // Masquer toutes les étapes
    steps.forEach((s) => s.classList.remove("active"));
    // Afficher l'étape courante
    steps[i].classList.add("active");

    // Afficher/masquer les boutons selon l'étape
    if (prevBtn) prevBtn.style.display = i === 0 ? "none" : "inline-block";
    if (nextBtn)
      nextBtn.style.display = i === steps.length - 1 ? "none" : "inline-block";
    if (submitBtn)
      submitBtn.style.display = i === steps.length - 1 ? "block" : "none";
  }

  function validateStep(stepIndex) {
    const step = steps[stepIndex];
    const requiredFields = step.querySelectorAll("[required]");
    let valid = true;

    // Supprimer l'ancien message d'erreur s'il existe
    const existingAlert = step.querySelector(".step-error-alert");
    if (existingAlert) existingAlert.remove();

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        // field.classList.add("is-invalid");
        valid = false;
      } else {
        // field.classList.remove("is-invalid");
      }
    });

    if (!valid) {
      const alert = document.createElement("div");
      alert.className = "alert alert-danger mt-2 step-error-alert";
      alert.textContent =
        "Veuillez remplir tous les champs obligatoires avant de continuer.";
      step.prepend(alert);
    }

    return valid;
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", function () {
      if (current < steps.length - 1) {
        if (!validateStep(current)) return;
        current++;
        showStep(current);
      }
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", function () {
      if (current > 0) {
        current--;
        showStep(current);
      }
    });
  }

  showStep(current); //Affiche la première étape au chargement
}

// DataTables initialization
$(document).ready(function () {
  // Configuration pour la table des personas
  if ($("#personasTable").length) {
    // Vérifier si la colonne de type existe (pour list-all-personas)
    var hasTypeColumn = $("#personasTable thead th").length > 4;

    var columnDefs = [
      { orderable: false, targets: 3 }, // Désactiver le tri sur la colonne Actions
    ];

    // Ajouter la configuration pour masquer la colonne Type uniquement si elle existe
    if (hasTypeColumn) {
      columnDefs.push({ visible: false, targets: 4 });
    }

    $("#personasTable").DataTable({
      // ... options
      language: {
        search: "Rechercher :", // label de champ de recherche
        lengthMenu: "_MENU_ entrées", // Sélecteur nombre d’entrées
        info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées", // Info pagination
        infoEmpty: "Affichage de 0 à 0 sur 0 entrées", // Quand la table est vide
        infoFiltered: "(filtré de _MAX_ entrées totales)", // Info filtrage
        paginate: {
          first: "<<",
          last: ">>",
          next: ">",
          previous: "<",
        },
        emptyTable: "Aucune donnée disponible", // Message table est vide
        zeroRecords: "Aucun enregistrement correspondant trouvé", // Message sans résultat
      },
      order: [[0, "asc"]], // Tri par nom par défaut
      columnDefs: columnDefs, // Variable définie plus haut
      pagingType: "full_numbers",
      pageLength: 10,
      lengthChange: true,
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
    });

    // Filtre personnalisé pour les personas
    if ($("#personaFilter").length) {
      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== "personasTable") {
          return true; // Ignore les autres tables
        }

        var filterValue = $("#personaFilter").val();
        var personaType = data[4]; // Colonne cachée avec le type de persona

        if (filterValue === "all") {
          return true; // Afficher tous les personas
        }

        return personaType === filterValue; // Affiche si correspondance
      });

      // Redessiner la table à chaque changement de filtre
      $("#personaFilter").on("change", function () {
        $("#personasTable").DataTable().draw();
      });

      // Appliquer le filtre initial au chargement de la page
      $("#personasTable").DataTable().draw();
    }
  }

  // Configuration pour la table des projets
  if ($("#operationsTable").length) {
    $("#operationsTable").DataTable({
      language: {
        search: "Rechercher :",
        lengthMenu: "_MENU_ entrées",
        info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
        infoEmpty: "Affichage de 0 à 0 sur 0 entrées",
        infoFiltered: "(filtré de _MAX_ entrées totales)",
        paginate: {
          first: "<<",
          last: ">>",
          next: ">",
          previous: "<",
        },
        emptyTable: "Aucune donnée disponible",
        zeroRecords: "Aucun enregistrement correspondant trouvé",
      },
      order: [[0, "asc"]], // Tri par nom par défaut
      columnDefs: [
        { orderable: false, targets: 1 }, // Désactiver le tri sur la colonne Actions
      ],
      pagingType: "full_numbers",
      pageLength: 10,
      lengthChange: true,
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
    });
  }

  // Configuration pour la table des utilisateurs
  if ($("#usersTable").length) {
    $("#usersTable").DataTable({
      language: {
        search: "Rechercher :",
        lengthMenu: "_MENU_ entrées",
        info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
        infoEmpty: "Affichage de 0 à 0 sur 0 entrées",
        infoFiltered: "(filtré de _MAX_ entrées totales)",
        paginate: {
          first: "<<",
          last: ">>",
          next: ">",
          previous: "<",
        },
        emptyTable: "Aucune donnée disponible",
        zeroRecords: "Aucun enregistrement correspondant trouvé",
      },
      order: [[0, "asc"]], // Tri par nom par défaut
      columnDefs: [
        { orderable: false, targets: 5 }, // Désactiver le tri sur la colonne Actions
      ],
      pagingType: "full_numbers",
      pageLength: 10,
      lengthChange: true,
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
    });
  }
});

// Gestion des critères pour le formulaire de création de persona
if (document.querySelector("[data-criteria-types]")) {
  // Récupérer les données depuis les attributs data
  const criteriaDataElement = document.querySelector("[data-criteria-types]");
  const criteriaTypes = JSON.parse(
    criteriaDataElement.getAttribute("data-criteria-types"),
  );
  const personaCriteriaForJs = JSON.parse(
    criteriaDataElement.getAttribute("data-persona-criteria") || "[]",
  );
  const isEditMode =
    criteriaDataElement.getAttribute("data-edit-mode") === "true";

  // Compteur pour chaque type de critère
  const criteriaCounters = {};

  // Fonction pour ajouter un champ critère avec autocomplétion
  window.addCriteria = function (typeId, selectedText = null) {
    if (!criteriaCounters[typeId]) {
      criteriaCounters[typeId] = 0;
    }
    criteriaCounters[typeId]++;
    const criteriaIndex = criteriaCounters[typeId];

    const container = document.getElementById(`criteria-container-${typeId}`);

    const criteriaDiv = document.createElement("div");
    criteriaDiv.className = "criteria-item mb-2 p-2 border rounded";
    criteriaDiv.id = `criteria-${typeId}-${criteriaIndex}`;

    const wrapper = document.createElement("div");
    wrapper.className = "d-flex align-items-center gap-2";

    // Wrapper relatif pour positionner le dropdown sous l'input
    const inputWrapper = document.createElement("div");
    inputWrapper.className = "position-relative flex-grow-1";

    // Champ texte
    const input = document.createElement("input");
    input.type = "text";
    input.name = `custom_criteria[${typeId}][]`;
    input.className = "form-control form-control-custom";
    input.placeholder = "Saisir un critère...";
    input.autocomplete = "off";
    input.required = true;
    if (selectedText) input.value = selectedText;

    // Liste de suggestions
    const dropdown = document.createElement("ul");
    dropdown.className =
      "criteria-suggestions list-group position-absolute w-100";
    dropdown.style.cssText =
      "top: 100%; left: 0; z-index: 1050; display: none; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 12px rgba(0,0,0,.15);";

    let debounceTimer = null;

    input.addEventListener("input", function () {
      clearTimeout(debounceTimer);
      const q = input.value.trim();
      if (q.length < 1) {
        dropdown.style.display = "none";
        dropdown.innerHTML = "";
        return;
      }
      debounceTimer = setTimeout(() => {
        fetch(
          `index.php?action=search-criteria&q=${encodeURIComponent(q)}&type_id=${typeId}`,
        )
          .then((r) => r.json())
          .then((results) => {
            dropdown.innerHTML = "";
            if (results.length === 0) {
              dropdown.style.display = "none";
              return;
            }
            results.forEach((item) => {
              const li = document.createElement("li");
              li.className = "list-group-item list-group-item-action py-2";
              li.style.cursor = "pointer";
              li.textContent = item.criterion_description;
              li.addEventListener("mousedown", function (e) {
                e.preventDefault(); // Empêche le blur avant la sélection
                input.value = item.criterion_description;
                dropdown.style.display = "none";
                dropdown.innerHTML = "";
              });
              dropdown.appendChild(li);
            });
            dropdown.style.display = "block";
          })
          .catch(() => {
            dropdown.style.display = "none";
          });
      }, 250);
    });

    input.addEventListener("blur", function () {
      setTimeout(() => {
        dropdown.style.display = "none";
      }, 150);
    });

    input.addEventListener("focus", function () {
      if (dropdown.children.length > 0) {
        dropdown.style.display = "block";
      }
    });

    inputWrapper.appendChild(input);
    inputWrapper.appendChild(dropdown);

    // Bouton supprimer
    const removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.className = "btn btn-danger btn-sm";
    removeButton.textContent = "Supprimer";
    removeButton.addEventListener("click", function () {
      window.removeCriteria(typeId, criteriaIndex);
    });

    wrapper.appendChild(inputWrapper);
    wrapper.appendChild(removeButton);
    criteriaDiv.appendChild(wrapper);
    container.appendChild(criteriaDiv);

    updateRemoveButtons(typeId);
  };

  // Met à jour la visibilité des boutons supprimer selon le nombre de champs
  function updateRemoveButtons(typeId) {
    const container = document.getElementById(`criteria-container-${typeId}`);
    if (!container) return;
    const items = container.querySelectorAll(".criteria-item");
    items.forEach((item) => {
      const btn = item.querySelector(".btn-danger");
      if (btn) {
        btn.disabled = items.length <= 1;
        btn.title = items.length <= 1 ? "Au moins un critère est requis" : "";
      }
    });
  }

  // Fonction pour supprimer un critère
  window.removeCriteria = function (typeId, id) {
    const container = document.getElementById(`criteria-container-${typeId}`);
    if (container && container.querySelectorAll(".criteria-item").length <= 1) {
      return; // Ne pas supprimer le dernier champ
    }
    const criteriaDiv = document.getElementById(`criteria-${typeId}-${id}`);
    if (criteriaDiv) {
      criteriaDiv.remove();
      updateRemoveButtons(typeId);
    }
  };

  // Initialiser les critères
  if (isEditMode && personaCriteriaForJs.length > 0) {
    // Mode édition : regrouper les critères par type et pré-remplir
    const criteriaByTypeMap = {};

    personaCriteriaForJs.forEach((criterion) => {
      const typeId = String(criterion.type_id);
      if (!criteriaByTypeMap[typeId]) {
        criteriaByTypeMap[typeId] = [];
      }
      criteriaByTypeMap[typeId].push(criterion.description);
    });

    criteriaTypes.forEach((type) => {
      const typeId = type.id_criteria_type;
      if (criteriaByTypeMap[typeId] && criteriaByTypeMap[typeId].length > 0) {
        criteriaByTypeMap[typeId].forEach((description) => {
          addCriteria(typeId, description);
        });
      } else {
        addCriteria(typeId);
      }
    });
  } else {
    // Mode création : un champ vide par type
    criteriaTypes.forEach((type) => {
      addCriteria(type.id_criteria_type);
    });
  }
}

// Gestion de l'inactivité et déconnexion automatique
if (window.userIsLoggedIn) {
  (function () {
    const WARNING_DELAY = 25 * 60 * 1000; // Avertissement à 25 min
    const LOGOUT_DELAY = 30 * 60 * 1000; // Déconnexion à 30 min

    let warningTimer, logoutTimer;

    function resetTimers() {
      clearTimeout(warningTimer);
      clearTimeout(logoutTimer);

      warningTimer = setTimeout(function () {
        alert(
          "Attention : vous serez déconnecté dans 5 minutes pour cause d'inactivité.",
        );
      }, WARNING_DELAY);

      logoutTimer = setTimeout(function () {
        window.location.href = "index.php?action=logout&inactivity=1";
      }, LOGOUT_DELAY);
    }

    // Réinitialiser les timers à chaque interaction utilisateur
    ["mousemove", "keydown", "click", "scroll", "touchstart"].forEach(
      function (event) {
        document.addEventListener(event, resetTimers, { passive: true });
      },
    );

    resetTimers(); // Démarrer au chargement de la page
  })();
}

/* ===== FORMULAIRE SWOT ===== */

(function () {
  const swotForm = document.getElementById('swotForm');
  if (!swotForm) return;

  // Délégation d'événement pour les boutons supprimer (statiques et dynamiques)
  ['strength', 'weakness', 'opportunity', 'threat'].forEach(function (category) {
    const container = document.getElementById('items-' + category);
    if (container) {
      container.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-swot-delete');
        if (btn) btn.closest('.swot-item-row').remove();
      });
    }
  });

  // Boutons "Ajouter"
  swotForm.querySelectorAll('.btn-add-swot-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const category = btn.dataset.category;
      const placeholder = btn.dataset.placeholder;
      const container = document.getElementById('items-' + category);
      const row = document.createElement('div');
      row.className = 'swot-item-row';

      const input = document.createElement('input');
      input.type = 'text';
      input.name = 'items[' + category + '][]';
      input.className = 'form-control form-control-sm swot-item-input';
      input.placeholder = placeholder;

      const deleteBtn = document.createElement('button');
      deleteBtn.type = 'button';
      deleteBtn.className = 'btn-swot-delete';
      deleteBtn.title = 'Supprimer';
      deleteBtn.innerHTML = '<i class="bi bi-x-lg"></i>';

      row.appendChild(input);
      row.appendChild(deleteBtn);
      container.appendChild(row);
      input.focus();
    });
  });
})();
