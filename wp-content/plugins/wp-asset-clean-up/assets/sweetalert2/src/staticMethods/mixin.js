/**
 * Returns an extended version of `wpacuSwal` containing `params` as defaults.
 * Useful for reusing wpacuSwal configuration.
 *
 * For example:
 *
 * Before:
 * const textPromptOptions = { input: 'text', showCancelButton: true }
 * const {value: firstName} = await wpacuSwal.fire({ ...textPromptOptions, title: 'What is your first name?' })
 * const {value: lastName} = await wpacuSwal.fire({ ...textPromptOptions, title: 'What is your last name?' })
 *
 * After:
 * const TextPrompt = wpacuSwal.mixin({ input: 'text', showCancelButton: true })
 * const {value: firstName} = await TextPrompt('What is your first name?')
 * const {value: lastName} = await TextPrompt('What is your last name?')
 *
 * @param mixinParams
 */
export function mixin (mixinParams) {
  class MixinSwal extends this {
    _main (params) {
      return super._main(Object.assign({}, mixinParams, params))
    }
  }

  return MixinSwal
}
