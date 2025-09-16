##### Objective-C

Use the `deleteDocumentWithCompletion:` method:

**Note:** This product is not available on watchOS and App Clip targets.

```
[[[self.db collectionWithPath:@"cities"] documentWithPath:@"DC"]
    deleteDocumentWithCompletion:^(NSError * _Nullable error) {
      if (error != nil) {
        NSLog(@"Error removing document: %@", error);
      } else {
        NSLog(@"Document successfully removed!");
      }
}];
ViewController.m
```

##### C++

```
// This is not supported. Delete data using CLI as discussed below.
  
```

##### Node.js

```
async function deleteCollection(db, collectionPath, batchSize) {
  const collectionRef = db.collection(collectionPath);
  const query = collectionRef.orderBy('__name__').limit(batchSize);

  return new Promise((resolve, reject) => {
    deleteQueryBatch(db, query, resolve).catch(reject);
  });
}

async function deleteQueryBatch(db, query, resolve) {
  const snapshot = await query.get();

  const batchSize = snapshot.size;
  if (batchSize === 0) {
    // When there are no documents left, we are done
    resolve();
    return;
  }

  // Delete documents in a batch
  const batch = db.batch();
  snapshot.docs.forEach((doc) => {
    batch.delete(doc.ref);
  });
  await batch.commit();

  // Recurse on the next process tick, to avoid
  // exploding the stack.
  process.nextTick(() => {
    deleteQueryBatch(db, query, resolve);
  });
}

index.js
```

# Piano di Lavoro: Integrazione FullCalendar

Questa è la pianificazione per l'integrazione e la configurazione di FullCalendar nel plugin WordPress.

- [x] Installare le dipendenze necessarie (`webpack`, `loaders`, `fullcalendar`).
- [x] Creare la configurazione base di Webpack (`webpack.config.js`).
- [x] Modificare il file JavaScript principale per usare `import`.
- [x] Risolvere i problemi di importazione dei fogli di stile (CSS).
- [x] Eseguire con successo il build di Webpack.
- [x] Aggiornare il codice PHP per accodare il nuovo file `bundle.js`.
- [x] Verificare il funzionamento del calendario nell'interfaccia di WordPress.
- [x] Pulire le vecchie referenze ai file JS/CSS non più necessari.
- [x] Documentare la procedura di build nel `README.md`.

# Piano di Lavoro: Automazione del Packaging

Questa è la pianificazione per automatizzare la creazione del pacchetto di distribuzione del plugin.

- [x] Installare la dipendenza di sviluppo `bestzip`.
- [x] Creare uno script `package` nel file `package.json`.
- [x] Configurare lo script per creare un file `wp-vet-plugin.zip` contenente solo i file di produzione.
- [ ] Documentare la procedura di packaging nel `README.md`.
