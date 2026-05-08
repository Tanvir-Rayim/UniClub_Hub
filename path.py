import os

def generate_tree(start_path, indent=""):
    tree = ""
    
    try:
        items = sorted(os.listdir(start_path))
    except PermissionError:
        return indent + "[Permission Denied]\n"

    for index, item in enumerate(items):
        path = os.path.join(start_path, item)
        connector = "└── " if index == len(items) - 1 else "├── "

        tree += indent + connector + item + "\n"

        if os.path.isdir(path):
            extension = "    " if index == len(items) - 1 else "│   "
            tree += generate_tree(path, indent + extension)

    return tree


# ===== CHANGE THIS PATH =====
folder_path = r"C:\Users\ASUS\Documents\CSE470\UniClubHub"

# Output file
output_file = "directory_tree.txt"

# Generate tree
directory_tree = folder_path + "\n"
directory_tree += generate_tree(folder_path)

# Save to txt file
with open(output_file, "w", encoding="utf-8") as file:
    file.write(directory_tree)

print(f"Directory tree saved to: {output_file}")