🧠 High-Level Flow Description

Image Displayed

The web app loads or renders a picture (user uploads, gallery view, etc.).

Face Detection

Detect one or more faces in the image (using a local model or the recognition API).

For Each Detected Face:

Query Recognition Service

Send face encoding or image snippet to the recognition API.

Match Found?

✅ Yes → Known Subject

Retrieve subject metadata (name, UUID, profile info, etc.).

Tag the photo (overlay label or update database entry).

❌ No → Unknown Subject

Register the new subject:

Send the image or face embedding to the recognition service.

Receive a unique ID/UUID.

Optionally prompt for a name or auto-assign a placeholder.

Store mapping in your database for future reference.

Update and Display

Update the photo’s metadata (with tag, UUID, or “Unknown #” label).

Refresh UI to show recognized subjects.

(Optional) Continuous Learning

As more photos are tagged, re-train or update embeddings with additional data.
