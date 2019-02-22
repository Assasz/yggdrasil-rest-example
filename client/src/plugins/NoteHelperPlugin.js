/**
 * NoteHelper plugin
 */
class NoteHelperPlugin {

    /**
     * Renders given note
     *
     * @param {object} note
     * @returns {string}
     */
    render(note) {
        let header = (note.title.length > 100) ?
            `<h2 id="note_title" data-toggle="tooltip" data-placement="bottom" title="${note.title}" data-original-title="${note.title}">${note.title.substring(0, 100) + '...'}</h2>` :
            `<h2 id="note_title">${note.title}</h2>`;

        return `
            <div id="note_${note.id}" class="card border-primary mb-3">
                <div class="card-header">
                    ${header}
                    <p class="text-muted my-2">${moment(note.createDate).format("MMM Do YY")}</p>
                </div>
                <div class="card-body">
                    <p id="note_content" class="card-text">${note.content}</p>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-sm btn-primary mr-2" data-action="toggleModal" data-target="edit_modal" data-note-id="${note.id}">
                        <span class="fa fa-fw fa-pencil mr-1" aria-hidden="true"></span>
                        Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-action="toggleModal" data-target="delete_modal" data-note-id="${note.id}">
                        <span class="fa fa-fw fa-trash mr-1" aria-hidden="true"></span>
                        Delete
                    </button>
                </div>
            </div>
        `;
    }
}