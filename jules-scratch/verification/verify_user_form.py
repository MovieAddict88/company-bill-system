from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # The base URL needs to be determined. Since it's a local PHP project,
    # it's likely running on localhost. I'll need to find the correct path.
    # I'll assume a standard localhost setup for now.
    # I will need to find out the correct URL.
    # Let's try to navigate to the login page.
    # I'll assume the project is in the webroot.
    base_url = "http://localhost/"

    try:
        page.goto(base_url + "login.php")

        # Log in
        page.get_by_placeholder("Username").fill("admin")
        page.get_by_placeholder("Password").fill("admin")
        page.get_by_role("button", name="Login").click()

        # Wait for navigation to the main page (e.g., index.php)
        expect(page).to_have_url(base_url + "index.php")

        # Navigate to the user page
        page.goto(base_url + "user.php")

        # Open the "Add User" modal
        page.get_by_role("button", name="ADD").click()

        # Wait for the modal to appear
        expect(page.get_by_role("heading", name="Insert Data")).to_be_visible()

        # Select the 'Employer' role
        page.get_by_label("Role").select_option("employer")

        # Assert that the location fields are now visible
        expect(page.get_by_label("Major Location")).to_be_visible()
        expect(page.get_by_label("Branch Location")).to_be_visible()

        # Take a screenshot
        page.screenshot(path="jules-scratch/verification/verification.png")

        print("Verification script ran successfully.")

    except Exception as e:
        print(f"An error occurred: {e}")
        # Take a screenshot on failure to help debug
        page.screenshot(path="jules-scratch/verification/error.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)