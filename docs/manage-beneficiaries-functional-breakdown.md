# Manage Beneficiaries: Functional Breakdown (Legacy dropapp)

**Source:** `boxwise/dropapp` @ `8608888f65c01b73364d99360abd7edb88788613`

| Area | File |
|---|---|
| List, filters, bulk actions | `include/people.php` |
| Deactivated tab | `include/people_deactivated.php` |
| Shared list/delete/move helpers | `library/lib/list.php` |
| Cypress E2E | `cypress/e2e/1_feature_tests/5_3_Manage_Beneficiaries.js` |

> Behaviour is derived from code reading. It has not been verified at runtime. Items marked **VERIFY** need confirmation before they go into the spec.

---

## 1. Scope and access

- Data is scoped to the currently selected camp.
- If no camp is selected, the page fails with: `The list of beneficiaries is not available when there is no camp selected`.
- Two tabs:
  - **All**: `?action=people`
  - **Deactivated**: `?action=people_deactivated`
- Bulk actions receive a list of IDs from the grid. The handler rejects any ID that does not belong to the current camp (see §6, access check).

---

## 2. Data retrieval (All tab)

### 2.1 Row scope

- `people.deleted IS NULL` and `people.camp_id = current camp`.
- Family members (`parent_id` set) are returned as rows alongside family heads and individuals.

### 2.2 Computed fields per row

| Field | Rule |
|---|---|
| `level` | `0` = family head or individual, `1` = family member (`parent_id` set) |
| `gender` | `M` → `Male`, `F` → `Female`, otherwise empty |
| `age` | Years (`" yrs"`) if the person is older than 730 days, otherwise months (`" mo"` for 1 month, `" mos"` otherwise) |
| `tokens` | `SUM(transactions.drops)` for the person. Shown **only for level 0**; members show empty. Label = camp currency name. |
| `last_activity` | `MAX(transactions.transaction_date)`, formatted `Y-m-d`. Returned **only when a filter is active** (see §4 and §5). |
| `tags` | Coloured labels from `tags_relations` (`object_type = "People"`, not removed) joined to `tags` (not deleted, same camp, type `All` or `People`), ordered by `tags.seq`. |
| `days_last_active` | Days since the latest of `created`, `modified`, `last_activity`. |
| `icons` | See §4.3. |

### 2.3 Default sort order

1. Family head's surname, then first name (or the person's own name if they have no head).
2. Within a family, the head row comes first, followed by members.
3. Members are sorted by their own surname, then first name.

### 2.4 Sorting mode ("Make sortable")

- Enabled via the **List settings** quick filter (option "Make sortable").
- Switches the list to manual ordering (drag and drop). Indentation is disabled.
- When off, drag is used to move people into families (`allowmove`, target level 1).

### 2.5 Row limit

- If the camp has **more than 500** people and no filter is applied, only the first 500 rows are shown.
- A notice is displayed: `Only the first 500 beneficiaries are shown. Use the filter and search to find the rest.`
- A "List size" filter with option **Show all** is offered in this case.
- Once any filter is active, the cap is lifted.

### 2.6 Totals row

- First cell: `Total`. Second cell: count of rows, e.g. `42 beneficiaries`.
- Count includes family members.
- If the cap applies and "Show all" is not selected, the label reads `500+ beneficiaries`.

---

## 3. Search

- Free-text search, case-insensitive `LIKE` (`%value%`) on:
  - `firstname`
  - `lastname`
  - `comments`
- **Container** (family identifier) is an **exact match**, not `LIKE`.
- Also searched, only if the corresponding camp feature is enabled:
  - `email`
  - `phone`
  - `customfield1_value`
  - `customfield2_value`
- `customfield3` and `customfield4` are **not** searchable.
- Search combines with quick filter and tag filter using AND.

> **Note for the new API:** the legacy code builds SQL by string concatenation after `db_escape`. Use bound parameters.

---

## 4. Filters

### 4.1 Quick filters (single select)

| Option | Label | Behaviour |
|---|---|---|
| `day` | New today | `created` is today |
| `week` | New this week | `created` is in the current ISO week |
| `month` | New this month | `created` is in the current month |
| `inactive` | Inactive | `days_last_active` > floor(`delete_inactive_users` / 2). Applied in PHP after the query. |
| `approvalsigned` | No signature | Family heads or individuals without a signed approval, plus members whose head has no signed approval. Rows are excluded if the person and their head are both signed. |
| `notregistered` | Not registered | `people.notregistered` is true. Always shown. |
| `volunteer` | Volunteers | `people.volunteer` is true. Shown **only if** the camp has `beneficiaryisvolunteer` enabled. |

### 4.2 Tag filter (multi-select)

- Options: all tags with type `All` or `People`, not deleted, same camp, ordered by `seq`.
- Logic: **OR**. Shows people who have any of the selected tags.

### 4.3 Icons (informational, not filters)

- **Inactive warning** (⚠): shown when `days_last_active` > threshold. Tooltip: `This family hasn't been active for at least N days.`
- **Missing signature** (edit icon): shown on heads or individuals with no signed approval. Links to `people_edit&id=…&active=signature`. Tooltip: `Please have the familyhead/beneficiary read and sign the approval form for storing and processing their data.`

### 4.4 Filter state effects

- Any active filter (quick, tag, or search) adds the **Last Activity** column.
- Any active filter removes the 500-row cap.

---

## 5. Columns

| # | Label | Source | Condition |
|---|---|---|---|
| 1 | Surname | `lastname` | Always |
| 2 | Firstname | `firstname` | Always |
| 3 | Gender | `gender` | Always |
| 4 | Age | `age` | Always |
| 5 | Container (camp's family identifier label) | `container` | Always |
| 6 | Tokens (camp currency name) | `tokens` | Always. Empty for members. |
| 7 | Tags | `tags` | Only if any tags exist |
| 8 | Comments | `comments` | Always |
| 9 | Last Activity | `last_activity` | Only when a filter is active |
| 10 | Email address | `email` | Only if email enabled |
| 11 | Phone number | `phone` | Only if phone enabled |
| 12 | Custom field 1 | `customfield1_value` | Only if enabled. Label from camp settings. |
| 13 | Custom field 2 | `customfield2_value` | Only if enabled. Label from camp settings. |
| 14 | Custom field 3 | `customfield3_value` | Only if enabled. Label from camp settings. |
| 15 | Custom field 4 | `customfield4_value` | Only if enabled. Label from camp settings. Date value, formatted `Y-m-d`. |
| — | Icons | `icons` | Always. Unlabelled. |

- Rows for family members are visually nested under their head (`level-1`).

---

## 6. Actions

### 6.1 Page-level

| Action | Behaviour | Visibility |
|---|---|---|
| New person | Opens `people_edit` form with no context | Always |
| Export all | Stores `all` in the export session, redirects to `people_export` | Only if ≥500 people **and** no filter is active. Note: `>=` here vs `>` for the row cap. |

### 6.2 Bulk actions on selected rows

| Action | Button label | Behaviour | Visibility / gating |
|---|---|---|---|
| Export | Export Selected | Stores selected IDs in session, redirects to `people_export` | Always |
| Add tag | Add Tag | Bulk-inserts into `tags_relations`, skipping people who already have the tag | Only if tags exist |
| Remove tag | Remove Tag | Sets `deleted_on` / `deleted_by_id` on matching `tags_relations` rows | Only if tags exist |
| Use service | Use Service | Inserts `services_relations` rows (no de-duplication) | Only if services exist **and** user has `register_service_usage` permission (level 4) |
| Give tokens | Give `<currency>` | Redirects to `give?ids=…` | Always |
| Merge to family | Merge to family | See 6.3 | Always |
| Detach from family | Detach from family | See 6.4 | Always |
| Print | Print | Redirects to `/pdf/{option}card.php?id=…`. Options: Bicycle card, Workshop card, ID Card. | Options depend on camp flags (`bicycle`, `idcard`). Button hidden if none. |
| Touch | Touch | Sets `modified` and `modified_by`, writes history entry `Touched` | Always |
| Move (drag) | — | Updates `parent_id` and `seq` for each [id, level] pair. Moves transactions to new head. | Only in move mode (not sortable mode) |
| Deactivate | Deactivate | Soft delete (`deleted = now`). Also deactivates all family members (recursive). Confirm dialog required. | Always |

### 6.3 Merge to family

- Input: 2 or more selected rows. None may be a family member already.
- The **oldest** selected person by `date_of_birth` becomes the family head. Others get `parent_id = head`.
- All transactions of the other selected people move to the head (`transactions.people_id`).
- After the merge, nested children are flattened (`correctchildren`): any child whose parent is itself a child is re-parented to the top-level head.
- Transaction block around the updates.

### 6.4 Detach from family

- Input: selected rows must all be family members (not heads).
- Sets `parent_id = NULL` for each.
- **Does not** move tokens back to the detached member (see §9).

### 6.5 Deactivate (soft delete)

- Confirm dialog: `Are you sure? You can not undo this anymore` (the text is shared with other list modules; the wording is misleading here because deactivate is reversible).
- Sets `deleted` on the person and recursively on their members.
- Preserved for `daystokeepdeletedpersons` days, then no longer visible in the Deactivated tab.

### 6.6 Move (drag, move mode)

- Each drop passes `[id, level]` pairs in the new order.
- Level 0 = head, level 1 = member of the nearest preceding head.
- Updates `parent_id` only where it changes. Updates `seq` per level.
- Transactions of a newly-attached member are moved to the new head.
- Calls a "correct drops" hook to update client-side token totals (see §9 item 4).

---

## 7. Validation and messages

### 7.1 Access control

- Every bulk action checks that all submitted IDs belong to the current camp.
- On failure: `You do not have access to this beneficiary record!` (raised as `E_USER_ERROR`, which may break the JSON response).

### 7.2 Merge

| Condition | Message |
|---|---|
| Any selected person is already a family member | `Please select only individuals or family heads to merge` |
| Fewer than two selected | `Please select more than one person to merge them into a family` |
| Success | `The merge has be successfully applied` (typo in source) |

### 7.3 Detach

| Condition | Message |
|---|---|
| Any selected person is a family head or individual | `Please select only members of a family, not family heads` |
| Success | `Selected people have been detached` |

### 7.4 Tag, Remove tag, Use service

| Condition | Message |
|---|---|
| No tags exist | `No tags exist. Please go to "Manage tags" to create tags.` |
| No services exist | `No service exist. Please go to "Manage Services" to create services.` (typo in source) |
| No rows selected (tag) | `To apply the tag, the beneficiary must be checked` / `To remove the tag, the beneficiary must be checked` |
| No rows selected (service) | `To record the service usage, the beneficiary must be checked` |
| Success | `Tags added` / `Tags removed` / `Service usage recorded` |

### 7.5 Touch

- Success: `Selected people have been touched`.

### 7.6 Deactivate / Full delete

- Success and error text come from translation keys `cms_list_deletesuccess` and `cms_list_deleteerror` (shared module).

---

## 8. Deactivated tab

### 8.1 Data

- Lists people with `deleted` newer than `now − daystokeepdeletedpersons` days, camp-scoped.
- Sorted by `deleted` descending (most recently deactivated first).
- Columns: Surname, Firstname, Head of Family, Gender, Age, Container, Tokens, Last active, enabled custom fields (1–4), icons.
- **Last active** for heads is the later of their last product transaction and `created`. For members it is empty.
- **Head of family** shows the head's name for members.
- Icons: warning when the member's head is not active. Tooltip: `To reactivate X please make sure you reactivate their family head (Y) first.`

### 8.2 Search

- Firstname, lastname, container, plus enabled email / phone / customfield1 / customfield2.
- Sortable (`allowsort`). No add, edit or copy.

### 8.3 Activate (undelete)

- Input: selected rows.
- For each selected row whose head is inactive **and** the head is not also selected, the action is rejected (all-or-nothing).
- Rejection message: `Family head X must be active before Y can be reactivated.` Multiple messages are concatenated with `<br>`.
- On success: restores selected rows (`deleted = 0`). Members are **not** restored automatically when their head is restored.
- Success: `Item recovered` (per Cypress spec; message key `cms_list_undeletesuccess`).

### 8.4 Full delete (hard delete)

- Confirm dialog required.
- For each selected person, in one transaction:
  1. Transactions are kept but unlinked (`people_id = NULL`).
  2. Their members have `parent_id` cleared.
  3. Tags are removed (`deleted_on` set).
  4. Row is hard-deleted, unless `preventdelete` is set.
- Success: `Item deleted` (per Cypress spec).

---

## 9. Known issues and open questions

1. **Missing camp check on Activate and Full delete.** `people_deactivated.php` does not verify that IDs belong to the current camp. The new API must enforce this on every mutation.
2. **Tag and service IDs** from the request are not validated against the current camp. Enforce in the new API.
3. **Merge** has an unused variable (`$extradrops`) and interpolates raw IDs into an `IN (…)` clause. The "oldest" rule breaks when `date_of_birth` is NULL. Decide the tie-breaker.
4. **Move and token correction (VERIFY).** `bulkcorrectdrops` appears to find no drops for moved people, because transactions are reassigned before it runs. If confirmed, the client-side token totals may not update after a move.
5. **Detach does not return tokens.** Decide whether tokens stay with the former family head or move with the member.
6. **Threshold mismatch.** The 500-row cap uses `>`. The export-all button uses `>=`. Pick one.
7. **Typos in user-facing strings:** `has be successfully applied`, `No service exist`. Fix in the new spec.
8. **Confirm dialog wording** for Deactivate is misleading because deactivation is reversible.
9. **Deactivate cascades** to all family members. Confirm whether that is intended.

---

## 10. Not covered in this breakdown

- Smarty template (`cms_list.tpl`) and front-end JavaScript (list rendering, drag-and-drop, row selection).
- Existing boxtribute beneficiary code. A gap analysis against boxtribute is a separate step.
- Export, print and give pages that the actions link to.
